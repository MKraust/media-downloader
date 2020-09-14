<?php

namespace App\Services\Media;

use App\Models;
use App\Tracker;
use App\Telegram;

class PostDownloadProcessor {

    /** @var Tracker\Keeper */
    private $_trackerKeeper;

    /** @var Telegram\Client */
    private $_telegram;

    public function __construct(Tracker\Keeper $trackerKeeper, Telegram\Client $telegram) {
        $this->_trackerKeeper = $trackerKeeper;
        $this->_telegram = $telegram;
    }

    public function processFinishedDownload(string $downloadHash, string $mediaPath): void {
        $download = Models\TorrentDownload::find($downloadHash);
        if ($download === null) {
            throw new \Exception("Download not found in database upon finish. Hash: {$downloadHash}");
        }

        $torrent = $download->torrent;
        $tracker = $this->_trackerKeeper->getTrackerById($torrent->media->tracker_id);
        $tracker->processDownloadedMedia($torrent, $mediaPath);

        $this->_telegram->notifyAboutFinishedDownload($download);
    }
}