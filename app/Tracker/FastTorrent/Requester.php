<?php

namespace App\Tracker\FastTorrent;

use App\Services\Http;

class Requester
{
    public const BASE_URL = 'http://fast-torrent.ru';

    private Http\Requester $_httpRequester;

    public function __construct() {
        $this->_httpRequester = app()->make(config('http.requesters.fast_torrent'));
    }

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
        return $this->_httpRequester->get($url);
    }

    public function getSearchUrl(string $query, int $page = 1): string {
        $query = urlencode($query);
        return self::BASE_URL . "/search/{$query}/{$page}.html";
    }

    public function getTorrentsUrlByMediaUrl(string $url): string {
        return str_replace('.html', '/torrents.html', $url);
    }
}
