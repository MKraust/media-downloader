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
            'long anime'                          => [
                'anime_long.html',
                [
                    'title'          => 'Ван Пис',
                    'original_title' => 'One Piece!',
                    'poster'         => 'https://static.animedia.tv/uploads/opwano.jpg',
                    'torrents'       => [
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=fec4d298896bc223d88ad56fc1e2d5e4&id=669',
                            'name'         => 'Серии 1-100',
                            'quality'      => '480p',
                            'size'         => '30.8 GB',
                            'downloads'    => 70198,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=a086e4b49133665915a03ab9fb338f64&id=670',
                            'name'         => 'Серии 101-200',
                            'quality'      => '480p',
                            'size'         => '30.8 GB',
                            'downloads'    => 55428,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=da65c06f5e77b4b8fc9d8a74bfd4eb15&id=671',
                            'name'         => 'Серии 201-300',
                            'quality'      => '480p',
                            'size'         => '34.6 GB',
                            'downloads'    => 51792,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=04dec4faf3a12c244cb71ae94ffefe9d&id=672',
                            'name'         => 'Серии 301-400',
                            'quality'      => '720p',
                            'size'         => '46.2 GB',
                            'downloads'    => 51487,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=ef8219cc580e95c8a1473c3e9c685c51&id=673',
                            'name'         => 'Серии 401-500',
                            'quality'      => '720p',
                            'size'         => '45.4 GB',
                            'downloads'    => 49056,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=a2391c5799be78da75a0dfa41cccb27e&id=674',
                            'name'         => 'Серии 501-600',
                            'quality'      => '720p',
                            'size'         => '41.2 GB',
                            'downloads'    => 49712,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=7d8566f1813d539afe8beaa89a0df60d&id=675',
                            'name'         => 'Серии 601-700',
                            'quality'      => '720p',
                            'size'         => '46.4 GB',
                            'downloads'    => 50536,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=626273d67a88607a1ecb9777da7be331&id=676',
                            'name'         => 'Серии 701-800',
                            'quality'      => '720p',
                            'size'         => '48.9 GB',
                            'downloads'    => 56973,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=fe988dfad236989302efb5c1f6342233&id=1265',
                            'name'         => 'Серии 801-900',
                            'quality'      => '720p',
                            'size'         => '37.3 GB',
                            'downloads'    => 24859,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=7edf24001a669b8eeedac72a19cccb14&id=1267',
                            'name'         => 'Серии 901-930',
                            'quality'      => '720р',
                            'size'         => '11.0 GB',
                            'downloads'    => 26189,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=e2a30337c49bf1ecfad540e53403281c&id=1342',
                            'name'         => 'Серия 931',
                            'quality'      => '1080p',
                            'size'         => '885.4 MB',
                            'downloads'    => 632,
                            'voice_acting' => 'FREYA & NAZEL',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                    ],
                ]
            ],
            'anime with movie'                    => [
                'anime_with_movie.html',
                [
                    'title'          => 'Семь смертных грехов',
                    'original_title' => 'Nanatsu no Taizai',
                    'poster'         => 'https://static.animedia.tv/uploads/1202111.jpg',
                    'torrents'       => [
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=f8f1183c1b3bfb0d206179bc41a151be&id=472',
                            'name'         => '1 Сезон (Серии 1-24 из 24)',
                            'season'       => [1, 24],
                            'quality'      => '720p',
                            'size'         => '10.4 GB',
                            'downloads'    => 44387,
                            'voice_acting' => 'KASHI & NAZEL & OZIRIST',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=327853c9ba5988347240c3581d29b384&id=473',
                            'name'         => 'ОВА (ОВА 1-4 из 4)',
                            'season'       => null,
                            'quality'      => '720p',
                            'size'         => '2.4 GB',
                            'downloads'    => 18358,
                            'voice_acting' => 'KASHI & NAZEL & OZIRIST',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=d32bde260d7bbde0067e038fd2fb4afe&id=974',
                            'name'         => '2 Сезон (Серии 1-24 из 24)',
                            'season'       => [2, 24],
                            'quality'      => '720p',
                            'size'         => '9.4 GB',
                            'downloads'    => 33108,
                            'voice_acting' => 'KASHI & NAZEL & OZIRIST',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=7c593bbcf606aeca96f8be44239313d4&id=1279',
                            'name'         => '3 Сезон (Серии 1-24 из 24)',
                            'season'       => [3, 24],
                            'quality'      => '720p',
                            'size'         => '8.9 GB',
                            'downloads'    => 19007,
                            'voice_acting' => 'KASHI & NAZEL & OZIRIST',
                            'content_type' => App\Models\Torrent::TYPE_ANIME,
                        ],
                        [
                            'url'          => 'https://tt.animedia.tv/?ACT=114&signature=240ebb01e58adf4480190ba8697c9a41&id=1169',
                            'name'         => 'Фильм: Узники небес (Фильм 1 из 1)',
                            'season'       => null,
                            'quality'      => '720p',
                            'size'         => '1.5 GB',
                            'downloads'    => 8130,
                            'voice_acting' => 'KASHI & NAZEL & OZIRIST',
                            'content_type' => App\Models\Torrent::TYPE_MOVIE,
                        ],
                    ],
                ],
            ],
//            'anime with single episode in season' => ['anime_season_single_episode.html'],
        ];
    }
}
