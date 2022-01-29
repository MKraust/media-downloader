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

    final public function startDownload(App\Models\Torrent $torrent): void {
        $fileContent = $this->loadTorrentFile($torrent->url);
        $fileName = Str::uuid() . '.torrent';
        $filePath = storage_path("app/public/torrents/{$fileName}");
        File::put($filePath, $fileContent);

        $fileUrl = url("/storage/torrents/{$fileName}");
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

        $media->load([
            'torrents' => function ($query) {
                $query->where('is_old', false);
            },
        ]);

        return $media;
    }

    private function _updateMediaWithData(App\Models\Media $media, array $data): void {
        $media->url            = $this->decryptUrl($media->id);
        $media->tracker_id     = $this->id();
        $media->title          = $media->title ?? $data['title'];
        $media->poster         = $media->poster ?? $data['poster'];
        $media->original_title = $media->original_title ?? $data['original_title'] ?? null;
        $media->series_count   = $data['series_count'] ?? $media->series_count ?? null;

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
            $this->_updateTorrentWithData($torrent, $media, $torrentData);
            $torrents->push($torrent);
        }

        $existingTorrents->diff($torrents)->each(function (App\Models\Torrent $torrent) {
            $torrent->is_old = true;
            $torrent->save();
        });
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
