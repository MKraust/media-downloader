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
        $url = str_replace('.html', '/torrents.html', $url);
        return $this->loadContent($url);
    }

    private function loadContent(string $url): string {
        return $this->getClient()->get($url)->getBody()->getContents();
    }

    private function getSearchUrl(string $query, int $page = 1): string {
        $query = urlencode($query);
        return self::BASE_URL . "/search/{$query}/{$page}.html";
    }

    private function getClient(): GuzzleHttp\Client {
        return new GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
    }
}
