<?php

namespace App\Services\Http;

class ProxyRequester extends Requester {

    protected function _getClientConfig(): array {
        return [
            'proxy'  => '127.0.0.1:9050',
            'verify' => true,
            'curl'   => [
                CURLOPT_PROXYTYPE => 7,
                CURLOPT_SSL_VERIFYPEER => 0,
            ],
        ];
    }
}
