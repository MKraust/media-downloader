<?php

namespace App\Tracker\FastTorrent;

use GuzzleHttp;

class Requester
{
    public const BASE_URL = 'http://fast-torrent.ru';

    public function search(string $query): string {
        $url = $this->getSearchUrl($query);
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
