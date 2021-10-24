<?php

namespace App\Services\Files;

class Renamer {

    public function renameDownloadedFiles(string $path): void {
        // exec("curl -G -X GET http://mkraust.ru/api/download/notify/finish --data-urlencode 'name={$name}'");

        if (mb_strpos($path, '/Anime') === false || !is_dir($path)) {
            exit;
        }

        $files = scandir($path.'/');
        if ($files === false) {
            exit;
        }

        $files = array_filter($files, fn($file) => !(mb_strpos($file, '.') === 0));
        $log = [];

        foreach ($files as $index => $file) {
            $newFileName = $this->normalizeFileName($file, $index + 1);
            rename("{$path}/{$file}", "{$path}/{$newFileName}");
            $log[] = [
                'from' => $file,
                'to'   => $newFileName,
            ];
            usleep(100);
        }

        $logJson = json_encode($log, JSON_PRETTY_PRINT);
        file_put_contents("{$path}/.rename.log", $logJson);
    }

    public function normalizeFileName(string $fileName, int $episodeIndex = 1): string {
        $parts = explode('.', $fileName);
        $extension = count($parts) > 1 ? array_pop($parts) : null;
        $fileNameWithoutExtension = implode('.', $parts);

        $newFileName = str_replace('_', ' ', $fileNameWithoutExtension);
        $newFileName = preg_replace('/\[[^]]*]/', '', $newFileName);
        $newFileName = preg_replace('/TV(-\d+)?/', '', $newFileName);
        $newFileName = str_replace('  ', ' ', $newFileName);

        $episode = $episodeIndex;
        $episodePatterns = [
            '~(?<=\[)\d+(?=\.\d+\])~',
            '~(?<=\[)\d+(?=\])~',
            '~(?<=\[)\d+(?=_of_\d+\])~',
            '~(?<=\[)\d+(?=_OVA])~',
        ];

        $season = null;
        foreach ($episodePatterns as $pattern) {
            preg_match($pattern, $fileName, $episodeMatches);
            if (count($episodeMatches) > 0) {
                $episode = (int)$episodeMatches[0];
                if ($pattern === $episodePatterns[0]) {
                    $season = 0;
                    break;
                }

                break;
            }
        }

        if ($season === null) {
            $season = 1;
            preg_match('/(?<=TV-)\d+/', $fileName, $seasonMatches);
            if (count($seasonMatches) > 0) {
                $season = (int)$seasonMatches[0];
            }
        }

        if ($season < 10) {
            $season = "0{$season}";
        }

        if ($episode < 10) {
            $episode = "00{$episode}";
        } else if ($episode < 100) {
            $episode = "0{$episode}";
        }

        $newFileName = "{$newFileName} - s{$season}e{$episode}" . ($extension ? ".{$extension}" : '');

        return trim(preg_replace('/\s+/', ' ', $newFileName));
    }
}