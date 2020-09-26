<?php

namespace App\Tracker;

use Illuminate\Support\Collection;
use App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Psr\Http\Message\StreamInterface;

abstract class BaseTracker
{
    /** @var App\Torrent\Client */
    private $_torrentClient;

    public function __construct() {
        $this->_torrentClient = app()->make(App\Torrent\Client::class);
    }

    abstract public function id(): string;

    abstract public function title(): string;

    abstract public function icon(): string;

    /**
     * @param string $query
     * @param int $offset
     * @return Collection|App\Models\Media[]
     */
    abstract public function search(string $query, int $offset): Collection;

    public function processDownloadedMedia(App\Models\Torrent $torrent, string $path): void {
        return;
        $torrent->download_path = $path;
        $this->_postDownload($torrent);
        $torrent->save();

    }

    private function _processFileDownload(App\Models\Torrent $torrent): void {
        // rename file to media original title or simply title
    }

    private function _processDirectoryDownload(App\Models\Torrent $torrent): void {
        $path = $torrent->download_path;
        $files = scandir($path . '/');
        if ($files === false) {
            return;
        }

        $files = array_filter($files, function ($file) {
            return Str::startsWith($file, '.');
        });

        $isEpisodesStartFromFirst = false;
        $episodeOffset = 0;

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
                '~(?<=\[)\d+(?=\.\d+\])~', '~(?<=\[)\d+(?=\])~', '~(?<=\[)\d+(?=_of_\d+\])~',
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

            if ($episode < 100 && $episode < 10) {
                $episode = "00{$episode}";
            } else if ($episode < 100) {
                $episode = "0{$episode}";
            }

            $newFileName = "{$newFileName} - s{$season}e{$episode}" . ($extension ? ".{$extension}" : '');
            $newFileName = str_replace('  ', ' ', $newFileName);

            rename("{$path}/{$file}", "{$path}/{$newFileName}");
            $log[] = [
                'from' => $file, 'to' => $newFileName,
            ];
            usleep(100);
        }

        $logJson = json_encode($log, JSON_PRETTY_PRINT);
        file_put_contents("{$path}/.rename.log", $logJson);
    }

    private function _postDownload(App\Models\Torrent $torrent): void {
        if (is_file($torrent->download_path)) {
            $this->_processFileDownload($torrent);
            return;
        }

        if (is_dir($torrent->download_path)) {
            $this->_processDirectoryDownload($torrent);
            return;
        }
    }

    final public function startDownload(App\Models\Torrent $torrent): void {
        $fileContent = $this->loadTorrentFile($torrent->url);
        $fileName = Str::uuid() . '.torrent';
        $filePath = storage_path("app/public/torrents/{$fileName}");
        File::put($filePath, $fileContent);

        $fileUrl = env('APP_URL') . "/storage/torrents/{$fileName}";
        $this->_torrentClient->startDownload($torrent, $fileUrl);
    }

    final public function serialize(): array {
        return [
            'id'         => $this->id(),
            'title'      => $this->title(),
            'icon'       => $this->icon(),
        ];
    }

    public function loadMediaById(string $id): ?App\Models\Media {
        $decryptedUrl = $this->decryptUrl($id);
        return $this->loadMediaByUrl($decryptedUrl);
    }

    abstract protected function loadTorrentFile(string $url): string;

    abstract protected function loadMediaByUrl(string $url): ?App\Models\Media;

    final public function encryptUrl(string $url): string {
        $preparedUrl = trim($url);
        $preparedUrl = preg_replace('/^https?:/u', '', $preparedUrl);

        return base64_encode($preparedUrl);
    }

    final public function decryptUrl(string $encryptedUrl): string {
        return base64_decode($encryptedUrl);
    }

    final protected function createMediaFromData(array $data): App\Models\Media {
        $preparedData = $this->_prepareMediaData($data);

        $id = $this->encryptUrl($preparedData['url']);
        $media = App\Models\Media::firstOrNew(['id' => $id]);

        $this->_updateMediaWithData($media, $preparedData);
        $this->_refreshTorrentsWithData($media, $preparedData['torrents'] ?? collect());

        $media->load(['torrents']);
        return $media;
    }

    private function _updateMediaWithData(App\Models\Media $media, array $data): void {
        $media->url            = $this->decryptUrl($media->id);
        $media->tracker_id     = $this->id();
        $media->title          = $media->title ?? $data['title'];
        $media->poster         = $media->poster ?? $data['poster'];
        $media->original_title = $media->original_title ?? $data['original_title'] ?? null;
        $media->series_count   = $media->series_count ?? $data['series_count'] ?? null;

        $media->save();
    }

    final protected function _refreshTorrentsWithData(App\Models\Media $media, Collection $torrentsData) {
        $existingTorrents = App\Models\Torrent::where('media_id', $media->id)->get();

        $torrents = collect();
        foreach ($torrentsData as $torrentData) {
            $existingTorrent = $existingTorrents->first(function (App\Models\Torrent $torrent) use ($torrentData) {
                return $this->_isTorrentMatchParsedData($torrent, $torrentData);
            });

            $torrent = $existingTorrent ?? new App\Models\Torrent(['media_id' => $media->id]);
//            try {
                $this->_updateTorrentWithData($torrent, $media, $torrentData);
//            } catch (\Throwable $e) {
//                dd([$torrentData, $torrent, $existingTorrent]);
//            }
            $torrents->push($torrent);
        }
    }

    protected function _isTorrentMatchParsedData(App\Models\Torrent $torrent, array $torrentData): bool {
        return $torrent->url === $torrentData['url'];
    }

    private function _updateTorrentWithData(App\Models\Torrent $torrent, App\Models\Media $media, array $data): void {
        $torrent->media_id     = $media->id;
        $torrent->name         = $data['name'];
        $torrent->content_type = $data['content_type'];
        $torrent->url          = $data['url'];
        $torrent->voice_acting = $data['voice_acting'] ?? null;
        $torrent->quality      = $data['quality'] ?? null;
        $torrent->size         = $data['size'] ?? null;
        $torrent->size_int     = $data['size_int'] ?? null;
        $torrent->downloads    = $data['downloads'] ?? null;
        $torrent->season       = $data['season'] ?? null;

        $torrent->save();
    }

    protected function _prepareMediaData(array $data): array {
        return $data;
    }
}
