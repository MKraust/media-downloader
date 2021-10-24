<?php

namespace App\Services\Http;

class ProxyRequester extends Requester {

    protected function _getProxy(): string {
        $proxyIp = env('PROXY_IP');
        $proxyPort = env('PROXY_PORT');
        $proxyLogin = env('PROXY_LOGIN');
        $proxyPass = env('PROXY_PASS');

        return "$proxyLogin:$proxyPass@$proxyIp:$proxyPort";
    }

    protected function _getClientConfig(): array {
        return [
            'proxy'  => $this->_getProxy(),
            'verify' => true,
            'curl'   => [
                CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5_HOSTNAME,
                CURLOPT_SSL_VERIFYPEER => 0,
            ],
        ];
    }
}
