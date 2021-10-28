<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App;

class NormalizeFileNameTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testExample(string $initialFileName, string $expectedFileName, int $episodeIndex = 1): void {
        $renamer = new App\Services\Files\Renamer;
        $normalizedFileName = $renamer->normalizeFileName($initialFileName, $episodeIndex);

        $this->assertEquals($expectedFileName, $normalizedFileName);
    }

    public function dataProvider(): array {
        return [
            [
                '[AniDub]_World_Trigger_TV-3_[01].mp4',
                'World Trigger - s03e001.mp4',
            ],
            [
                'Yakusoku_no_Neverland_[01]_[AniMedia.TV].mp4',
                'Yakusoku no Neverland - s01e001.mp4',
            ],
            [
                'Yakusoku_no_Neverland_[02]_[AniMedia.TV].mp4',
                'Yakusoku no Neverland - s01e002.mp4',
            ],
            [
                '[AniDub]_Kono_Subarashii_Sekai_ni_Shukufuku_wo!_TV_[01]_[720p_x264_Aac]_[Ancord_Trina_D].mp4',
                'Kono Subarashii Sekai ni Shukufuku wo! - s01e001.mp4',
            ],
            [
                '[AniDub]_Kono_Subarashii_Sekai_ni_Shukufuku_wo!_TV_[02]_[720p_x264_Aac]_[Ancord_Trina_D].mp4',
                'Kono Subarashii Sekai ni Shukufuku wo! - s01e002.mp4',
            ],
            [
                '[AniDub]_Kono_Subarashii_Sekai_ni_Shukufuku_wo!_TV_[11_OVA]_[720p_x264_Aac]_[Ancord_Trina_D].mp4',
                'Kono Subarashii Sekai ni Shukufuku wo! - s01e011.mp4',
            ],
            [
                '[AniDub]_Kono_Subarashii_Sekai_ni_Shukufuku_wo!_TV_[02]_[720p_x264_Aac]_[Ancord_Trina_D].mp4',
                'Kono Subarashii Sekai ni Shukufuku wo! - s01e002.mp4',
            ],
            [
                '[AniDub]_Kono_Subarashii_Sekai_ni_Shukufuku_wo_TV-2_[01]_[720p]_[Ancord_Trina_D].mp4',
                'Kono Subarashii Sekai ni Shukufuku wo - s02e001.mp4',
            ],
            [
                '[AniDub]_Kono_Subarashii_Sekai_ni_Shukufuku_wo_TV-2_[10]_[720p]_[Ancord_Trina_D].mp4',
                'Kono Subarashii Sekai ni Shukufuku wo - s02e010.mp4',
            ],
            [
                '[AniDub]_Kono_Subarashii_Sekai_ni_Shukufuku_wo_TV-2_[OVA]_[1080p]_[Ancord_Trina_D].mp4',
                'Kono Subarashii Sekai ni Shukufuku wo - s02e001.mp4',
            ],
            [
                '[AniDub]_Kono_Subarashii_Sekai_ni_Shukufuku_wo_TV-2_[OVA]_[1080p]_[Ancord_Trina_D].mp4',
                'Kono Subarashii Sekai ni Shukufuku wo - s02e011.mp4',
                11
            ],
            [
                'Nanatsu_no_Taizai_3_[23]_[AniMedia.TV].mp4',
                'Nanatsu no Taizai - s03e023.mp4',
            ]
        ];
    }
}