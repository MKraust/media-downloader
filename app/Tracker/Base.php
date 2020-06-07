<?php

namespace App\Tracker;

use Illuminate\Support\Collection;
use App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Psr\Http\Message\StreamInterface;

abstract class Base
{
    abstract public function id(): string;

    abstract public function title(): string;

    /**
     * @param string $query
     * @return Collection|Media[]
     */
    abstract public function search(string $query): Collection;

    final public function startDownload(string $url, string $contentType): void {
        $fileContent = $this->loadTorrentFile($url);

        $fileName = Str::uuid() . '.torrent';
        $filePath = storage_path("app/public/torrents/{$fileName}");
        File::put($filePath, $fileContent);

        $fileUrl = url("/storage/torrents/{$fileName}");
        $torrentClient = new App\Torrent\Client();
        $torrentClient->startDownload($fileUrl, $contentType);
    }

    final public function serialize(): array {
        return [
            'id'    => $this->id(),
            'title' => $this->title(),
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
        $media = new Media;

        $url = $data['url'] ?? null;
        $media->id = $url !== null ? $this->encryptUrl($url) : null;
        $media->title = $data['title'] ?? null;
        $media->poster = $data['poster'] ?? null;

        $torrents = $data['torrents'] ?? collect();
        $media->torrents = $torrents->toArray();

        return $media;
    }
}
