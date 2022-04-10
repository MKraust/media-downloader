<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinishedDownload;
use App\Torrent;
use App\Http\Requests;
use App\Services;
use App\Telegram;
use Illuminate\Http\Request;

class DownloadsController extends Controller
{
    private Torrent\Client $_torrentClient;

    private Services\Files\Renamer $_filesRenamer;

    private Telegram\Client $_telegram;

    public function __construct(
        Torrent\Client $torrentClient,
        Services\Files\Renamer $filesRenamer,
        Telegram\Client $telegram,
        Services\Files\Service $files
    ) {
        $this->_torrentClient = $torrentClient;
        $this->_filesRenamer = $filesRenamer;
        $this->_telegram = $telegram;
        $this->_files = $files;
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
        $finishedDownload->meta = [
            'files'      => $this->_files->getFilesByPath($request->path),
            'rename_log' => $this->_filesRenamer->normalizeFileNames($request->path, $torrent),
        ];
        $finishedDownload->save();

        $this->_telegram->notifyAboutFinishedDownload($torrent);
    }

    public function getFinishedDownloads() {
        return FinishedDownload::with(['torrent.media'])
            ->orderBy('finished_at', 'desc')
            ->get();
    }

    public function deleteFinishedDownload(Request $request) {
        $request->validate([
            'id' => 'required|exists:finished_downloads',
        ]);

        $download = FinishedDownload::find($request->id);
        $this->_files->deleteRecursively($download->path);

        return $download;
    }
}
