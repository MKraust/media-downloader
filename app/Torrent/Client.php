<?php

namespace App\Torrent;

use App\Jobs\RefreshTorrentDownloads;
use App\Models\Torrent;
use App\Models\TorrentDownload;
use GuzzleHttp;
use App\Telegram;
use Illuminate\Support\Collection;

class Client
{
    private const BASE_URL = 'http://torrent.mkraust.ru';

    private const GET_TORRENTS = '/query/torrents';

    private const START_DOWNLOAD_URL = '/command/download';
    private const DELETE_DOWNLOAD = '/command/deletePerm';
    private const PAUSE_DOWNLOAD = '/command/pause';
    private const RESUME_DOWNLOAD = '/command/resume';

    private const BASE_SAVE_PATH = '/home/kraust/Public/Video/';

    private $_telegram;

    public function __construct() {
        $this->_telegram = new Telegram\Client();
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
        $params = [
            'query' => [
                'hashes' => implode('|', $hashes),
            ],
        ];

        $response = $this->_getClient()->get(self::GET_TORRENTS, $params)->getBody()->getContents();
        return collect(json_decode($response, true));
    }

    public function startDownload(Torrent $torrent, string $fileUrl): void {
        $httpClient = $this->_getClient();
        $httpClient->post(self::START_DOWNLOAD_URL, [
            'multipart' => [
                [
                    'name' => 'urls',
                    'contents' => $fileUrl,
                ],
                [
                    'name' => 'savepath',
                    'contents' => self::BASE_SAVE_PATH . $this->_getDirectoryByContentType($torrent->content_type),
                ],
                [
                    'name' => 'rename',
                    'contents' => "id:{$torrent->id}",
                ]
            ],
        ]);

        RefreshTorrentDownloads::dispatch()->delay(now()->addSeconds(5));
    }

    public function deleteDownload(string $hash): void {
        $download = TorrentDownload::find($hash);
        $download->is_deleted = true;
        $download->save();

        $this->_getClient()->post(self::DELETE_DOWNLOAD, [
            'form_params' => [
                'hashes' => $hash,
            ],
        ]);
    }

    public function pauseDownload(string $hash): void {
        $this->_getClient()->post(self::PAUSE_DOWNLOAD, [
            'form_params' => [
                'hash' => $hash,
            ],
        ]);
    }

    public function resumeDownload(string $hash): void {
        $this->_getClient()->post(self::RESUME_DOWNLOAD, [
            'form_params' => [
                'hash' => $hash,
            ],
        ]);
    }

    private function _getDirectoryByContentType(string $contentType): string {
        switch ($contentType) {
            case Torrent::TYPE_ANIME:
                return 'Anime';
            case Torrent::TYPE_SERIES:
                return 'Serials';
            case Torrent::TYPE_MOVIE:
                return 'Movies';
        }
    }

    private function _getClient(): GuzzleHttp\Client {
        return new GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
    }
}
