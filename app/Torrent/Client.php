<?php

namespace App\Torrent;

use App\Models\TorrentDownload;
use GuzzleHttp;

class Client
{
    private const BASE_URL = 'http://torrent.mkraust.ru';

    private const GET_TORRENTS = '/query/torrents';

    private const START_DOWNLOAD_URL = '/command/download';
    private const DELETE_DOWNLOAD = '/command/deletePerm';
    private const PAUSE_DOWNLOAD = '/command/pause';
    private const RESUME_DOWNLOAD = '/command/resume';

    private const BASE_SAVE_PATH = '/home/kraust/Public/Video/';

    public function refreshDownloads() {
        $downloadsData = $this->_getDownloadsData();

        $existingDownloads = TorrentDownload::all();
        $downloads = collect($downloadsData)->map(function (array $torrentData) {
            return new TorrentDownload([
                'hash' => $torrentData['hash'],
                'name' => $torrentData['name'],
            ]);
        });

        $newDownloads = $downloads->filter(function (TorrentDownload $download) use ($existingDownloads) {
            return !$existingDownloads->contains('hash', $download->hash);
        });

        $removedDownloads = $existingDownloads->filter(function (TorrentDownload $download) use ($downloads) {
            return !$downloads->contains('hash', $download->hash);
        });

        $newDownloads->each->save();
        $removedDownloads->each->delete();
    }

    public function getDownloads(): array {
        $downloads = TorrentDownload::active()->get();
        $hashes = $downloads->map->hash->toArray();

        return $this->_getDownloadsData($hashes);
    }

    /**
     * @param string[] $hashes
     * @return array
     */
    private function _getDownloadsData(array $hashes = []): array {
        $params = [
            'query' => [
                'hashes' => implode('|', $hashes),
            ],
        ];

        $response = $this->_getClient()->get(self::GET_TORRENTS, $params)->getBody()->getContents();
        return json_decode($response, true);
    }

    public function startDownload(string $fileUrl, string $contentType): void {
        $httpClient = $this->_getClient();
        $httpClient->post(self::START_DOWNLOAD_URL, [
            'multipart' => [
                [
                    'name' => 'urls',
                    'contents' => $fileUrl,
                ],
                [
                    'name' => 'savepath',
                    'contents' => self::BASE_SAVE_PATH . $this->_getDirectoryByContentType($contentType),
                ]
            ],
        ]);

        $this->refreshDownloads();
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
            case 'anime':
                return 'Anime';
            case 'series':
                return 'Serials';
            case 'movie':
                return 'Movies';
        }
    }

    private function _getClient(): GuzzleHttp\Client {
        return new GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
    }
}
