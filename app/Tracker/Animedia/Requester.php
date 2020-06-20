<?php


namespace App\Tracker\Animedia;

use GuzzleHttp;
use Illuminate\Support\Collection;

class Requester
{
    public const BASE_URL = 'https://tt.animedia.tv';

    private const MEDIA_PER_PAGE = 40;

    public function search(string $searchQuery, int $offset): string {
        $page = (int)floor($offset / self::MEDIA_PER_PAGE);
        $response = $this->getClient()->get("/ajax/search_result/P{$page}", [
            'query' => [
                'limit'        => self::MEDIA_PER_PAGE,
                'keywords'     => $searchQuery,
                'orderby_sort' => 'view_count_one|desc',
            ],
        ]);

        return $response->getBody()->getContents();
    }

    public function loadMediaPage(string $url): string {
        $response = $this->getClient()->post($url);

        return $response->getBody()->getContents();
    }

    public function loadTorrentFile(string $url): string {
        $response = $this->getClient()->get($url);

        return $response->getBody()->getContents();
    }

    private function getClient(): GuzzleHttp\Client {
        return new GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
    }
}
