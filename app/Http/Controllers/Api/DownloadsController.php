<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Torrent;
use App\Http\Requests;

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

    public function deleteDownload(Requests\Torrent\ManageDownload $request) {
        $this->_torrentClient->deleteDownload($request->hash);
    }

    public function pauseDownload(Requests\Torrent\ManageDownload $request) {
        $this->_torrentClient->pauseDownload($request->hash);
    }

    public function resumeDownload(Requests\Torrent\ManageDownload $request) {
        $this->_torrentClient->resumeDownload($request->hash);
    }
}
