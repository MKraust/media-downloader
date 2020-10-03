<?php

namespace App\Services\Media;

use App\Models;
use App\Tracker;
use App\Telegram;
use Illuminate\Support\Str;

class PostDownloadProcessor {

    /** @var Telegram\Client */
    private $_telegram;

    public function __construct(Tracker\Keeper $trackerKeeper, Telegram\Client $telegram) {
        $this->_telegram = $telegram;
    }

    public function processFinishedDownload(string $downloadHash, string $mediaPath): void {
        $download = Models\TorrentDownload::find($downloadHash);
        if ($download === null) {
            throw new \Exception("Download not found in database upon finish. Hash: {$downloadHash}");
        }

        $torrent = $download->torrent;
        $this->_processDownload($torrent, $mediaPath);

        $this->_telegram->notifyAboutFinishedDownload($download);
    }

    private function _processDownload(Models\Torrent $torrent, string $path): void {
        if (is_file($path)) {
            $this->_processFileDownload($torrent, $path);
            return;
        }

        if (is_dir($path)) {
            $this->_processDirectoryDownload($torrent, $path);
            return;
        }
    }

    private function _processFileDownload(Models\Torrent $torrent, string $path): void {
        // rename file to media original title or simply title
    }

    private function _processDirectoryDownload(Models\Torrent $torrent, string $path): void {
        $files = scandir($path . '/');
        if ($files === false) {
            return;
        }

        $files = array_filter($files, function ($file) {
            return Str::startsWith($file, '.');
        });

        $log = [];

        foreach ($files as $index => $file) {
            $parts = explode('.', $file);
            $extension = count($parts) > 1 ? array_pop($parts) : null;
            $fileNameWithoutExtension = implode('.', $parts);

            $newFileName = str_replace('_', ' ', $fileNameWithoutExtension);
            $newFileName = preg_replace('/\[[^\]]*\]/', '', $newFileName);
            $newFileName = str_replace('  ', ' ', $newFileName);

            $episode = $index + 1;

            $episodePatterns = [
                '~(?<=\[)\d+(?=\.\d+\])~',
                '~(?<=\[)\d+(?=\])~',
                '~(?<=\[)\d+(?=_of_\d+\])~',
            ];

            $season = null;
            foreach ($episodePatterns as $pattern) {
                preg_match($pattern, $file, $episodeMatches);
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
                preg_match('/(?<=TV-)\d+/', $file, $seasonMatches);
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
            $newFileName = str_replace('  ', ' ', $newFileName);

            rename("{$path}/{$file}", "{$path}/{$newFileName}");
            $log[] = ['from' => $file, 'to' => $newFileName];
            usleep(100);
        }

        $logJson = json_encode($log, JSON_PRETTY_PRINT);
        file_put_contents("{$path}/.rename.log", $logJson);
    }
}