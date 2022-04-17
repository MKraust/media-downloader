<?php

namespace App\Services\Files;

use App\Models\Torrent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

    public function revertFileNames(string $path, array $renameLogs): void {
        foreach ($renameLogs as $log) {
            $this->_renameFile($path, $log['to'], $log['from']);
        }
    }

    public function normalizeFileNames(string $path, ?Torrent $torrent = null): array {
        if (!str_contains($path, '/Anime') || !is_dir($path)) {
            return [];
        }

        $files = scandir($path.'/');
        if ($files === false) {
            exit;
        }

        $log = [];
        $alreadyRenamedFiles = collect($this->getRenamedFiles($path));
        Log::info("Already renamed files: \n" . implode("\n", $alreadyRenamedFiles->map->to()->toArray()));
        $files = array_filter($files, function (string $file) use ($alreadyRenamedFiles) {
            return !(mb_strpos($file, '.') === 0)
                && !$alreadyRenamedFiles->contains(fn(RenamedFile $renamedFile) => $file === $renamedFile->to());
        });
        Log::info("Files to rename: \n" . implode("\n", $files));

        // Тут берем сезон из торрента
        // Если же его нет, то null потом перетрется сезоном из названия файла
        $season = $torrent->season;
        $season = $season !== null && $season[0] > 0 ? $season[0] : null;
        foreach ($files as $index => $file) {
            $newFileName = $this->normalizeFileName($file, $index + 1, $season);
            $log[] = $this->_renameFile($path, $file, $newFileName);
            usleep(100);
        }

        return array_merge($alreadyRenamedFiles->toArray(), $log);
    }

    public function renameFilesWithTitle(Torrent $torrent, string $path, array $fileNames, string $title, ?int $season = null): array {
        $preparedSeason = $season ?? $torrent->season;
        $preparedSeason = $preparedSeason !== null && $preparedSeason[0] > 0 ? $preparedSeason[0] : null;
        $log = [];
        foreach ($fileNames as $index => $fileName) {
            $info = $this->_getInfoFromFileName($fileName, $index + 1, $preparedSeason);
            $newFileName = $this->_getFileName($title, $info['episode'], $info['season'], $info['extension']);

            $log[] = $this->_renameFile($path, $fileName, $newFileName);
            usleep(100);
        }

        return $log;
    }

    private function _getFileName(string $title, string $episode, string $season, string $extension): string {
        $newFileName = "{$title} - s{$season}e{$episode}" . ($extension ? ".{$extension}" : '');
        return trim(preg_replace('/\s+/', ' ', $newFileName));
    }

    private function _getInfoFromFileName(string $initialFileName, int $startIndex = 1, int $season = null): array {
        $parts = explode('.', $initialFileName);
        $extension = count($parts) > 1 ? array_pop($parts) : null;
        $fileNameWithoutExtension = implode('.', $parts);

        $newFileName = preg_replace('/(?<=_)\d+(?=_\[)/', '', $fileNameWithoutExtension);
        $newFileName = str_replace('_', ' ', $newFileName);
        $newFileName = preg_replace('/\[[^]]*]/', '', $newFileName);
        $newFileName = preg_replace('/TV(-\d+)?/', '', $newFileName);
        $newFileName = preg_replace('/ +/', ' ', $newFileName);

        $episode = $startIndex;
        $episodePatterns = [
            '~(?<=\[)\d+(?=\.\d+\])~',
            '~(?<=\[)\d+(?=_OVA])~',
            '~(?<=\[)\d+(?=\])~',
            '~(?<=\[)\d+(?=_of_\d+\])~',
        ];

        foreach ($episodePatterns as $pattern) {
            preg_match($pattern, $initialFileName, $episodeMatches);
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
            preg_match('/((?<=TV-)\d+)|((?<=_)\d+(?=_\[))/', $initialFileName, $seasonMatches);
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

        return [
            'title' => $newFileName,
            'episode' => $episode,
            'season' => $season,
            'extension' => $extension,
        ];
    }

    public function normalizeFileName(string $fileName, int $episodeIndex = 1, int $season = null): string {
        $info = $this->_getInfoFromFileName($fileName, $episodeIndex, $season);
        return $this->_getFileName($info['title'], $info['episode'], $info['season'], $info['extension']);
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

    private function _renameFile(string $path, string $from, string $to): RenamedFile {
        File::move("{$path}/{$from}", "{$path}/{$to}");
        return new RenamedFile($from, $to);
    }
}
