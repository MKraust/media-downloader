<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Torrent;
use App\Http\Requests;
use App\Services;

class DownloadsController extends Controller
{
    private Torrent\Client $_torrentClient;

    private Services\Files\Renamer $_filesRenamer;

    public function __construct(Torrent\Client $torrentClient, Services\Files\Renamer $filesRenamer) {
        $this->_torrentClient = $torrentClient;
        $this->_filesRenamer = $filesRenamer;
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

    public function finishDownload(Requests\Torrent\FinishDownload $request) {
        $this->_filesRenamer->normalizeFileNames($request->path);
    }
}
