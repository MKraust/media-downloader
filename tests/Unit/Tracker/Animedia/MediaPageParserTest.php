<?php

namespace Tests\Unit\Tracker\Animedia;

use Tests\TestCase;
use App;

class MediaPageParserTest extends TestCase
{
    /**
     * @dataProvider htmlProvider
     */
    public function testExample(string $htmlFileName, array $expectedResult): void
    {
        $html = file_get_contents(base_path('tests/Unit/Tracker/Animedia/html/' . $htmlFileName));
        $parser = new App\Tracker\Animedia\MediaPageParser();
        $itemData = $parser->parse($html);
        $itemData['torrents'] = $itemData['torrents']->toArray();

        $this->assertEquals($expectedResult, $itemData);
    }

    public function htmlProvider(): array {
        return [
            'common anime'                        => [
                'anime_common.html',
                [
                    'title'          => 'Башня Бога',
                    'original_title' => 'Kami no Tou',
                    'poster'         => 'https://static.animedia.tv/uploads/god_tower.jpg',
                    'torrents'       => [
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=d0a6540f309798f299525c24a74073ec&id=1314',
                            'name'         => '1 Сезон (Серии 1-13 из 13)',
                            'season'       => [1, 13],
                            'quality'      => '720p',
                            'size'         => '4.8 GB',
                            'downloads'    => 10870,
                            'voice_acting' => 'KovarnyBober & Neonoir & Wicked_Wayfarer',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                    ],
                ],
            ],
//            'long anime'                          => ['anime_long.html'],
//            'anime with movie'                    => ['anime_with_movie.html'],
//            'anime with single episode in season' => ['anime_season_single_episode.html'],
        ];
    }
}
