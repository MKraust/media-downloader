<?php

namespace Tests\Unit\Tracker\Anidub;

use Tests\TestCase;
use App;

class MediaPageParserTest extends TestCase
{
    /**
     * @dataProvider htmlProvider
     */
    public function testExample(string $htmlFileName, array $expectedResult): void
    {
        $html = file_get_contents(base_path('tests/Unit/Tracker/Anidub/html/' . $htmlFileName));
        $parser = new App\Tracker\Anidub\MediaPageParser();
        $itemData = $parser->parse($html);
        $itemData['torrents'] = $itemData['torrents']->toArray();

        $this->assertEquals($expectedResult, $itemData);
    }

    public function htmlProvider(): array {
        return [
            'common anime' => [
                'anime_common.html',
                [
                    'title'          => 'Семь смертных грехов',
                    'original_title' => 'Nanatsu no Taizai - The Seven Deadly Sins',
                    'series_count'   => '24 из 24+2OVA',
                    'poster'         => 'https://static2.statics.life/tracker/poster/597ff40303.jpg',
                    'torrents'       => [
                        [
                            'url'          => 'https://tr.anidub.com/engine/download.php?id=11442',
                            'name'         => 'TV (720p)',
                            'season'       => [1, 24],
                            'quality'      => 'TV (720p)',
                            'size'         => '10.48 GB',
                            'downloads'    => 32438,
                            'voice_acting' => 'JAM',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tr.anidub.com/engine/download.php?id=11886',
                            'name'         => 'BD (1080p)',
                            'season'       => [1, 24],
                            'quality'      => 'BD (1080p)',
                            'size'         => '51.09 GB',
                            'downloads'    => 2316,
                            'voice_acting' => 'JAM',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tr.anidub.com/engine/download.php?id=11453',
                            'name'         => 'HWP',
                            'season'       => [1, 24],
                            'quality'      => 'HWP',
                            'size'         => '7.10 GB',
                            'downloads'    => 948,
                            'voice_acting' => 'JAM',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tr.anidub.com/engine/download.php?id=11451',
                            'name'         => 'PSP',
                            'season'       => [1, 24],
                            'quality'      => 'PSP',
                            'size'         => '2.86 GB',
                            'downloads'    => 2642,
                            'voice_acting' => 'JAM',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                    ],
                ],
            ],
        ];
    }
}
