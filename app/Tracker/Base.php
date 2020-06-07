<?php

namespace App\Tracker;

use Illuminate\Support\Collection;
use App;
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
        $filePath = $this->loadTorrentFile($url);
        $torrentClient = new App\Torrent\Client();
        $torrentClient->startDownload($filePath, $contentType);
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
}
