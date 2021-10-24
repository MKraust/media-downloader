<?php

namespace App\Services\Files;

use Illuminate\Support\Facades\File;

class Renamer {

    private const RENAMED_FILES_LOG_NAME = '.rename.log';

    /**
     * @return RenamedFile[]
     */
    public function getRenamedFiles(string $path): array {
        $logPath = $this->_getRenamedFilesLogPath($path);
        if (!File::exists($logPath)) {
            return [];
        }

        $json = file_get_contents($logPath);
        $filesData = json_decode($json, true);

        return array_map(fn(array $fileData) => new RenamedFile($fileData['from'], $fileData['to']), $filesData);
    }

    public function normalizeFileNames(string $path): void {
        if (mb_strpos($path, '/Anime') === false || !is_dir($path)) {
            exit;
        }

        $files = scandir($path.'/');
        if ($files === false) {
            exit;
        }

        $log = [];
        $alreadyRenamedFiles = collect($this->getRenamedFiles($path));
        $files = array_filter($files, function (string $file) use ($alreadyRenamedFiles) {
            return !(mb_strpos($file, '.') === 0)
                && !$alreadyRenamedFiles->contains(fn(RenamedFile $renamedFile) => $file === $renamedFile->from());
        });

        foreach ($files as $index => $file) {
            $newFileName = $this->normalizeFileName($file, $index + 1);
            rename("{$path}/{$file}", "{$path}/{$newFileName}");
            $log[] = new RenamedFile($file, $newFileName);
            usleep(100);
        }

        $log = array_merge($alreadyRenamedFiles, $log);
        $this->_saveRenamedFilesLog($path, $log);
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

    private function _getRenamedFilesLogPath(string $path): string {
        return "{$path}/" . self::RENAMED_FILES_LOG_NAME;
    }

    /**
     * @param RenamedFile[] $files
     */
    private function _saveRenamedFilesLog(string $path, array $files): void {
        $logJson = json_encode($files, JSON_PRETTY_PRINT);
        $logPath = $this->_getRenamedFilesLogPath($path);
        file_put_contents($logPath, $logJson);
    }
}