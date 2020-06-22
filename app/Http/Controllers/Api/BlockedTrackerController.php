<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Torrent;
use App\Tracker;
use App\Http\Requests;
use Illuminate\Http\Request;

class BlockedTrackerController extends Controller
{
    /** @var Tracker\BlockedTracker */
    private $_tracker;

    public function __construct(Request $trackerRequest, Tracker\Keeper $trackerKeeper)
    {
        $trackerId = $trackerRequest->tracker_id;
        $this->_tracker = $trackerKeeper->getTrackerById($trackerId);
    }

    public function getSearchUrl()
    {
        $query = request('query');

        return $this->_tracker->getSearchingUrl($query);
    }

    public function parseSearchResult()
    {
        $html = request('html');

        return $this->_tracker->parseSearchResults($html);
    }

    public function getMediaUrls()
    {
        $mediaId = request('id');

        return $this->_tracker->getMediaUrls($mediaId);
    }

    public function parseMedia()
    {
        $mediaId = request('id');
        $htmlParts = request('html');

        return $this->_tracker->parseMedia($mediaId, $htmlParts);
    }

    public function downloadFromFile()
    {
        $torrentId = request('id');
        $file = request()->file('file')->get();

        $torrent = Torrent::find($torrentId);
        $this->_tracker->startDownloadFromFile($file, $torrent);

        return response()->json(['status' => 'success']);
    }
}
