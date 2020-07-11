<?php


namespace App\Tracker\Animedia;

use App\Services\Http\ProxyRequester;
use GuzzleHttp;

class Requester
{
    public const BASE_URL = 'https://tt.animedia.tv';

    private const MEDIA_PER_PAGE = 40;

    /** @var ProxyRequester */
    private $_httpRequester;

    public function __construct() {
        $this->_httpRequester = app()->make(ProxyRequester::class);
    }

    public function search(string $searchQuery, int $offset): string {
        $page = (int)floor($offset / self::MEDIA_PER_PAGE);
        return $this->_httpRequester->get(self::BASE_URL . "/ajax/search_result/P{$page}", [
            'limit'        => self::MEDIA_PER_PAGE,
            'keywords'     => $searchQuery,
            'orderby_sort' => 'view_count_one|desc',
        ]);
    }

    public function loadMediaPage(string $url): string {
        return $this->_httpRequester->get($url);
    }

    public function loadTorrentFile(string $url): string {
        return $this->_httpRequester->get($url);
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
