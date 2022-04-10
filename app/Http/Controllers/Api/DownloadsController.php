<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinishedDownload;
use App\Torrent;
use App\Http\Requests;
use App\Services;
use App\Telegram;

class DownloadsController extends Controller
{
    private Torrent\Client $_torrentClient;

    private Services\Files\Renamer $_filesRenamer;

    private Telegram\Client $_telegram;

    public function __construct(
        Torrent\Client $torrentClient,
        Services\Files\Renamer $filesRenamer,
        Telegram\Client $telegram,
    ) {
        $this->_torrentClient = $torrentClient;
        $this->_filesRenamer = $filesRenamer;
        $this->_telegram = $telegram;
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
        $torrentId = str_replace('id:', '', $request->name);
        $torrent = \App\Models\Torrent::find($torrentId);

        $finishedDownload = new FinishedDownload;
        $finishedDownload->torrent_id = $torrentId;
        $finishedDownload->finished_at = now();
        $finishedDownload->path = $request->path;
        $finishedDownload->save();

        $this->_filesRenamer->normalizeFileNames($request->path, $torrent);
        $this->_telegram->notifyAboutFinishedDownload($torrent);
    }

    public function getFinishedDownloads() {
        return FinishedDownload::orderBy('finished_at', 'desc')->get();
    }
}
