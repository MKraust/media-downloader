<?php

namespace App\Services\Http;

use GuzzleHttp;

class Requester {

    public function get(string $url, array $params = [], ?GuzzleHttp\Cookie\CookieJar $cookies = null): string {
        $client = $this->_getClient();

        $requestConfig = [];
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

        $requestConfig = ['form_params' => $params];
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
        ]);

        return $response->getBody()->getContents();
    }

    public function getPostCookies(string $url, array $params): GuzzleHttp\Cookie\CookieJar {
        $cookies = new GuzzleHttp\Cookie\CookieJar();
        $this->_getClient()->post($url, [
            'form_params' => $params,
            'cookies'     => $cookies,
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