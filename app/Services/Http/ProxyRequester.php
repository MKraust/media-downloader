<?php

namespace App\Services\Http;

class ProxyRequester extends Requester {

    protected function _getClientConfig(): array {
        $proxyIp = env('PROXY_IP');
        $proxyPort = env('PROXY_PORT');
        $proxyLogin = env('PROXY_LOGIN');
        $proxyPass = env('PROXY_PASS');

        return [
            'proxy'  => "$proxyLogin:$proxyPass@$proxyIp:$proxyPort",
            'verify' => true,
            'curl'   => [
                CURLOPT_PROXYTYPE => 7,
                CURLOPT_SSL_VERIFYPEER => 0,
            ],
        ];
    }
}
