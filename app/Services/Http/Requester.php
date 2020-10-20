<?php

namespace App\Services\Http;

use GuzzleHttp;

class Requester {

    public function get(string $url, array $params = [], ?GuzzleHttp\Cookie\CookieJar $cookies = null): string {
        $client = $this->_getClient();

        $requestConfig = [
            'headers'     => [
                'User-Agent' => 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0',  
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
                'User-Agent' => 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0',  
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
        $requestConfig = [
            'headers'     => [
                'User-Agent' => 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0',  
            ],
        ];
        
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
            'headers'     => [
                'User-Agent' => 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0',  
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
