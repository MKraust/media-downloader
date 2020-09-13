<?php

namespace App\Torrent;

use App\Jobs\RefreshTorrentDownloads;
use App\Models\Torrent;
use App\Models\TorrentDownload;
use App\Services\Http\Requester;
use App\Telegram;
use Illuminate\Support\Collection;

class Client
{
    private const BASE_URL = 'http://qbittorrent:8080/api/v2';

    private const GET_TORRENTS = self::BASE_URL . '/torrents/info';

    private const START_DOWNLOAD_URL = self::BASE_URL . '/torrents/add';
    private const DELETE_DOWNLOAD    = self::BASE_URL . '/torrents/delete';
    private const PAUSE_DOWNLOAD     = self::BASE_URL . '/torrents/pause';
    private const RESUME_DOWNLOAD    = self::BASE_URL . '/torrents/resume';

    private const BASE_SAVE_PATH = '/media/';

    /** @var Telegram\Client */
    private $_telegram;

    /** @var Requester */
    private $_httpRequester;

    private $_cookies;

    public function __construct(Telegram\Client $telegram, Requester $requester) {
        $this->_telegram = $telegram;
        $this->_httpRequester = $requester;

        $this->_cookies = $this->_httpRequester->getPostCookies(self::BASE_URL . '/auth/login', [
            'username' => 'admin',
            'password' => 'adminadmin',
        ]);
    }

    public function refreshDownloads() {
        $downloadsData = $this->_getDownloadsData();

        $existingDownloads = TorrentDownload::all();
        $downloads = $downloadsData->map(static function (array $downloadData) {
            return Download::createFromRemoteData($downloadData)->convertIntoModel();
        });

        $newDownloads = $downloads->filter(static function (TorrentDownload $download) use ($existingDownloads) {
            return !$existingDownloads->contains('hash', $download->hash);
        });

        $removedDownloads = $existingDownloads->filter(static function (TorrentDownload $download) use ($downloads) {
            return !$downloads->contains('hash', $download->hash);
        });

        $newDownloads->each->save();
        $removedDownloads->each(function (TorrentDownload $download) {
            if (!$download->is_deleted) {
                $this->_telegram->notifyAboutFinishedDownload($download);
            }

            $download->delete();
        });
    }

    /**
     * @return Collection|Download[]
     */
    public function getDownloads(): Collection {
        $downloads = TorrentDownload::active()->get();
        $hashes = $downloads->map->hash->toArray();

        $downloadsData = $this->_getDownloadsData($hashes);
        return $downloadsData
            ->filter(static function (array $downloadData) {
                return preg_match('/^id:\d+$/', $downloadData['name']);
            })
            ->map(static function (array $downloadData) {
                return Download::createFromRemoteData($downloadData);
            });
    }

    /**
     * @param string[] $hashes
     * @return Collection
     */
    private function _getDownloadsData(array $hashes = []): Collection {
        $params = count($hashes) > 0 ? ['hashes' => implode('|', $hashes)] : [];

        $response = $this->_httpRequester->get(self::GET_TORRENTS, $params, $this->_cookies);
        return collect(json_decode($response, true));
    }

    public function startDownload(Torrent $torrent, string $fileUrl): void {
        $this->_httpRequester->postMultipart(self::START_DOWNLOAD_URL, [
            'urls'     => $fileUrl,
            'savepath' => self::BASE_SAVE_PATH . $this->_getDirectoryByContentType($torrent->content_type),
            'rename'   => "id:{$torrent->id}",
        ], $this->_cookies);

        RefreshTorrentDownloads::dispatch()->delay(now()->addSeconds(5));
    }

    public function deleteDownload(string $hash): void {
        $download = TorrentDownload::find($hash);
        $download->is_deleted = true;
        $download->save();

        $this->_httpRequester->post(self::DELETE_DOWNLOAD, [
            'hashes' => $hash,
        ]);
    }

    public function pauseDownload(string $hash): void {
        $this->_httpRequester->post(self::PAUSE_DOWNLOAD, [
            'hash' => $hash,
        ]);
    }

    public function resumeDownload(string $hash): void {
        $this->_httpRequester->post(self::RESUME_DOWNLOAD, [
            'hash' => $hash,
        ]);
    }

    private function _getDirectoryByContentType(string $contentType): string {
        switch ($contentType) {
            case Torrent::TYPE_ANIME:
                return 'anime';
            case Torrent::TYPE_SERIES:
                return 'serials';
            case Torrent::TYPE_MOVIE:
                return 'movies';
        }
    }
}
