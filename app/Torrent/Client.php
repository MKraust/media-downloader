<?php

namespace App\Torrent;

use App\Models\TorrentDownload;
use GuzzleHttp;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\StreamInterface;

class Client
{
    private const BASE_URL = 'http://torrent.mkraust.ru';
    private const START_DOWNLOAD_URL = '/command/download';
    private const GET_TORRENTS = '/query/torrents';

    private const BASE_SAVE_PATH = '/home/kraust/Public/Video/';

    public function refreshDownloads() {
        $response = $this->_getClient()->get(self::GET_TORRENTS)->getBody()->getContents();
        $torrentsData = json_decode($response, true);

        $existingDownloads = TorrentDownload::all();
        $downloads = collect($torrentsData)->map(function (array $torrentData) {
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
