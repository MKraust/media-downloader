<?php

namespace App\Tracker;

use Illuminate\Support\Collection;
use App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Psr\Http\Message\StreamInterface;

abstract class BaseTracker
{
    abstract public function id(): string;

    abstract public function title(): string;

    abstract public function icon(): string;

    /**
     * @param string $query
     * @param int $offset
     * @return Collection|Media[]
     */
    abstract public function search(string $query, int $offset): Collection;

    final public function startDownload(string $url, string $contentType): void {
        $fileContent = $this->loadTorrentFile($url);
        $this->startDownloadFromFile($fileContent, $contentType);
    }

    final public function startDownloadFromFile(string $fileContent, $contentType): void {
        $fileName = Str::uuid() . '.torrent';
        $filePath = storage_path("app/public/torrents/{$fileName}");
        File::put($filePath, $fileContent);

        $fileUrl = url("/storage/torrents/{$fileName}");
        $torrentClient = new App\Torrent\Client();
        $torrentClient->startDownload($fileUrl, $contentType);
    }

    final public function serialize(): array {
        return [
            'id'         => $this->id(),
            'title'      => $this->title(),
            'icon'       => $this->icon(),
            'is_blocked' => $this instanceof BlockedTracker,
        ];
    }

    public function loadMediaById(string $id): ?Media {
        $decryptedUrl = $this->decryptUrl($id);
        return $this->loadMediaByUrl($decryptedUrl);
    }

    abstract protected function loadTorrentFile(string $url): string;

    abstract protected function loadMediaByUrl(string $url): ?Media;

    final protected function encryptUrl(string $url): string {
        return encrypt($url);
    }

    final protected function decryptUrl(string $encryptedUrl): string {
        return decrypt($encryptedUrl);
    }

    final protected function createMediaFromData(array $data): Media {
        $preparedData = $this->prepareMediaData($data);

        $media = new Media;

        $url                  = $preparedData['url'] ?? null;
        $media->id            = $url !== null ? $this->encryptUrl($url) : null;
        $media->title         = $preparedData['title'] ?? null;
        $media->originalTitle = $preparedData['original_title'] ?? null;
        $media->seriesCount   = $preparedData['series_count'] ?? null;
        $media->poster        = $preparedData['poster'] ?? null;

        $torrents             = $preparedData['torrents'] ?? collect();
        $media->torrents      = $torrents->sortByDesc('size_int')->values()->all();

        return $media;
    }

    protected function prepareMediaData(array $data): array {
        return $data;
    }
}
