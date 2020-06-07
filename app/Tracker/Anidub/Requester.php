<?php

namespace App\Tracker\Anidub;

use GuzzleHttp;
use Illuminate\Support\Facades\Cache;

class Requester
{
    private const COOKIE_CACHE_KEY = 'anidub_cookies';
    private const COOKIES_LIFETIME = 3600 * 24;

    public const BASE_URL = 'https://tr.anidub.com';

    public function search(string $searchQuery): string {
        $params = [
            "do" => "search",
            "subaction" => "search",
            "search_start" => 1,
            "full_search" => 1,
            "result_from" => 1,
            "story" => $searchQuery,
            "titleonly" => 0,
            "searchuser" => "",
            "replyless" => 0,
            "replylimit" => 0,
            "searchdate" => 0,
            "beforeafter" => "after",
            "sortby" => "",
            "resorder" => "desc",
            "showposts" => 0,
            "catlist" => [3, 10, 11, 14],
        ];

        $response = $this->getClient()->post('/', [
            'cookies' => $this->getCookies(),
            'form_params' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    public function loadMediaPage(string $url): string {
        $response = $this->getClient()->post($url, [
            'cookies' => $this->getCookies(),
        ]);

        return $response->getBody()->getContents();
    }

    private function getCookies(): GuzzleHttp\Cookie\CookieJar {
//        return Cache::remember(self::COOKIE_CACHE_KEY, 1, function () {
            $parameters = [
                'login_name'     => 'Kraust',
                'login_password' => '80578057Qw',
                'login'          => 'submit'
            ];

            $jar = new GuzzleHttp\Cookie\CookieJar();
            $this->getClient()->post('/', [
                'form_params' => $parameters,
                'cookies'     => $jar,
            ]);

            return $jar;
//        });
    }

    private function getClient(): GuzzleHttp\Client {
        return new GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
    }
}