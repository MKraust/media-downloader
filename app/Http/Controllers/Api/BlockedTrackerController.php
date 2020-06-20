<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tracker;
use App\Http\Requests;

class BlockedTrackerController extends Controller
{
    /** @var Tracker\BlockedTracker */
    private $_tracker;

    public function __construct(Requests\TrackerRequest $trackerRequest, Tracker\Keeper $trackerKeeper)
    {
        $trackerId = $trackerRequest->tracker;
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

        return $this->_tracker->parseMedia($mediaId, $htmlParts)->jsonSerialize();
    }

    public function downloadFromFile()
    {
        $contentType = request('type');
        $file = request()->file('file')->get();

        $this->_tracker->startDownloadFromFile($file, $contentType);

        return response()->json(['status' => 'success']);
    }
}
