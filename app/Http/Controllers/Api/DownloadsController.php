<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Torrent;

class DownloadsController extends Controller
{
    /** @var Torrent\Client */
    private $_torrentClient;

    public function __construct(Torrent\Client $torrentClient) {
        $this->_torrentClient = $torrentClient;
    }

    public function getDownloads() {
        return $this->_torrentClient->getDownloads();
    }

    public function deleteDownload() {
        $this->_torrentClient->deleteDownload(request('hash'));
    }

    public function pauseDownload() {
        $this->_torrentClient->pauseDownload(request('hash'));
    }

    public function resumeDownload() {
        $this->_torrentClient->resumeDownload(request('hash'));
    }
}
