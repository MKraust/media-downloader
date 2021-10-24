<?php

namespace App\Services\Http;

class TorProxyRequester extends ProxyRequester {

    protected function _getProxy(): string {
        return '127.0.0.1:9050';
    }
}
