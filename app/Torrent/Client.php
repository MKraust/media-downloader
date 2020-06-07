<?php

namespace App\Torrent;

use GuzzleHttp;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\StreamInterface;

class Client
{
    private const BASE_URL = 'http://torrent.mkraust.ru';
    private const START_DOWNLOAD_URL = '/command/download';

    private const BASE_SAVE_PATH = '/home/kraust/Public/Video/';

    public function startDownload(string $fileUrl, string $contentType): void {
        $httpClient = new GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
        $httpClient->post(self::START_DOWNLOAD_URL, [
            'form_params' => [
                [
                    'name' => 'urls',
                    'contents' => $fileUrl,
                ],
                [
                    'name' => 'savepath',
                    'contents' => self::BASE_SAVE_PATH . $this->getDirectoryByContentType($contentType),
                ]
            ],
        ]);
    }

    private function getDirectoryByContentType(string $contentType): string {
        switch ($contentType) {
            case 'anime':
                return 'Anime';
            case 'series':
                return 'Serials';
            case 'movie':
                return 'Movies';
        }
    }
}
