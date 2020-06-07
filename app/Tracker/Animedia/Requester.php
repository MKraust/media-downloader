<?php


namespace App\Tracker\Animedia;

use GuzzleHttp;
use Illuminate\Support\Collection;

class Requester
{
    private const BASE_URL = 'https://tt.animedia.tv';
    private const ANIME_LIST_URL = '/ajax/anime_list';

    public function getMediaList(): string {
        $response = $this->getClient()->get(self::ANIME_LIST_URL);

        return $response->getBody()->getContents();
    }

    private function getClient(): GuzzleHttp\Client {
        return new GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
    }
}
