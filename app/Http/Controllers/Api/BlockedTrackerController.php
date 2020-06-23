<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Torrent;
use App\Tracker;
use App\Http\Requests;
use Illuminate\Http\Request;

class BlockedTrackerController extends Controller
{
    /** @var Tracker\Keeper */
    private $_trackerKeeper;

    public function __construct(Tracker\Keeper $trackerKeeper)
    {
        $this->_trackerKeeper = $trackerKeeper;
    }

    public function getSearchUrl(Requests\Tracker\Blocked\GetSearchUrl $request)
    {
        $query = $request->search_query;
        $trackerId = $request->tracker_id;

        return $this->_trackerKeeper->getTrackerById($trackerId)->getSearchingUrl($query);
    }

    public function parseSearchResult(Requests\Tracker\Blocked\ParseSearchResults $request)
    {
        $html = $request->html;
        $trackerId = $request->tracker_id;

        return $this->_trackerKeeper->getTrackerById($trackerId)->parseSearchResults($html);
    }

    public function getMediaUrls(Requests\Tracker\Blocked\GetMediaUrls $request)
    {
        $mediaId = $request->id;
        $trackerId = Media::find($mediaId)->tracker_id;

        return $this->_trackerKeeper->getTrackerById($trackerId)->getMediaUrls($mediaId);
    }

    public function parseMedia(Requests\Tracker\Blocked\ParseMedia $request)
    {
        $mediaId = $request->id;
        $htmlParts = $request->html_parts;

        $trackerId = Media::find($mediaId)->tracker_id;

        return $this->_trackerKeeper->getTrackerById($trackerId)->parseMedia($mediaId, $htmlParts);
    }

    public function downloadFromFile(Requests\Tracker\Blocked\DownloadFromFile $request)
    {
        $torrentId = $request->id;
        $file = $request->file('file')->get();

        $torrent = Torrent::find($torrentId);
        $trackerId = $torrent->media->tracker_id;
        $this->_trackerKeeper->getTrackerById($trackerId)->startDownloadFromFile($file, $torrent);

        return response()->json(['status' => 'success']);
    }
}
