<?php

namespace App\Tracker\FastTorrent;

use GuzzleHttp;

class Requester
{
    public const BASE_URL = 'http://fast-torrent.ru';

    public function loadTorrentFile(string $url): string {
        return $this->loadContent($url);
    }

    public function search(string $query): string {
        $url = $this->getSearchUrl($query);
        return $this->loadContent($url);
    }

    public function loadMediaPage(string $url): string {
        return $this->loadContent($url);
    }

    public function loadTorrentsHtml(string $url): string {
        $url = $this->getTorrentsUrlByMediaUrl($url);
        return $this->loadContent($url);
    }

    private function loadContent(string $url): string {
        $config = [
            'config' => [
                'curl' => [
                    'CURLOPT_PROXY' => 'socks5://127.0.0.1:9050',
                    'CURLOPT_HTTPPROXYTUNNEL' => 1,
                ],
            ],
        ];

        return $this->getClient()->get($url)->getBody()->getContents();
    }

    public function getSearchUrl(string $query, int $page = 1): string {
        $query = urlencode($query);
        return self::BASE_URL . "/search/{$query}/{$page}.html";
    }

    public function getTorrentsUrlByMediaUrl(string $url): string {
        return str_replace('.html', '/torrents.html', $url);
    }

    private function getClient(): GuzzleHttp\Client {
        $config = [
            'base_uri' => self::BASE_URL,
            'proxy' => '127.0.0.1:9050', //use without "socks5://" scheme
            'verify' => true, // used only for SSL check , u can set false too for not check
            'curl' => [CURLOPT_PROXYTYPE => 7],
        ];
        return new GuzzleHttp\Client($config);
    }
}
