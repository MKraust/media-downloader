<?php

namespace App\Tracker\Anidub;

use App\Services\HttpRequester\ProxyRequester;
use GuzzleHttp;

class Requester
{
    public const BASE_URL = 'https://tr.anidub.com';

    /** @var ProxyRequester */
    private $_httpRequester;

    public function __construct() {
        $this->_httpRequester = app()->make(ProxyRequester::class);
    }

    public function search(string $searchQuery, int $offset): string {
        $page = (int)floor($offset / 15) + 1;
        $params = [
            "do" => "search",
            "subaction" => "search",
            "search_start" => $page,
            "full_search" => 1,
            "result_from" => $offset + 1,
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

        $cookies = $this->getCookies();
        return $this->_httpRequester->post(self::BASE_URL, $params, $cookies);
    }

    public function loadMediaPage(string $url): string {
        $cookies = $this->getCookies();
        return $this->_httpRequester->get($url, [], $cookies);
    }

    public function loadTorrentFile(string $url): string {
        $cookies = $this->getCookies();
        return $this->_httpRequester->get($url, [], $cookies);
    }

    private function getCookies(): GuzzleHttp\Cookie\CookieJar {
        $parameters = [
            'login_name'     => 'Kraust',
            'login_password' => '80578057Qw',
            'login'          => 'submit'
        ];

        return $this->_httpRequester->getPostCookies(self::BASE_URL, $parameters);
    }
}
