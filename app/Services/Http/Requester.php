<?php

namespace App\Services\Http;

use GuzzleHttp;

class Requester {

    private const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36';

    public function get(string $url, array $params = [], ?GuzzleHttp\Cookie\CookieJar $cookies = null): string {
        $client = $this->_getClient();

        $requestConfig = [
            'headers'     => [
                'User-Agent' => self::USER_AGENT,
            ],
        ];
        if (count($params) > 0) {
            $requestConfig['query'] = $params;
        }
        if ($cookies !== null) {
            $requestConfig['cookies'] = $cookies;
        }

        $response = $client->get($url, $requestConfig);

        return $response->getBody()->getContents();
    }

    public function post(string $url, array $params = [], ?GuzzleHttp\Cookie\CookieJar $cookies = null): string {
        $client = $this->_getClient();

        $requestConfig = [
            'form_params' => $params,
            'headers'     => [
                'User-Agent' => self::USER_AGENT,
            ],
        ];
        if ($cookies !== null) {
            $requestConfig['cookies'] = $cookies;
        }

        $response = $client->post($url, $requestConfig);

        return $response->getBody()->getContents();
    }

    public function postMultipart(string $url, array $params = []): string {
        $client = $this->_getClient();
        $requestConfig = [];

        foreach ($params as $key => $value) {
            $requestConfig[] = [
                'name'     => $key,
                'contents' => $value,
            ];
        }

        $response = $client->post($url, [
            'multipart' => $requestConfig,
            'headers'   => [
                'User-Agent' => self::USER_AGENT,
            ],
        ]);

        return $response->getBody()->getContents();
    }

    public function getPostCookies(string $url, array $params): GuzzleHttp\Cookie\CookieJar {
        $cookies = new GuzzleHttp\Cookie\CookieJar();
        $this->_getClient()->post($url, [
            'form_params' => $params,
            'cookies'     => $cookies,
            'headers'     => [
                'User-Agent' => self::USER_AGENT,
            ],
        ]);

        return $cookies;
    }

    protected function _getClientConfig(): array {
        return [];
    }

    private function _getClient(): GuzzleHttp\Client {
        $config = $this->_getClientConfig();
        return new GuzzleHttp\Client($config);
    }
}
