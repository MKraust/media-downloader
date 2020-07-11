<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Torrent;
use App\Tracker;
use App\Http\Requests;

class TrackerController extends Controller
{
    /** @var Tracker\Keeper */
    private $_trackerKeeper;

    public function __construct(Tracker\Keeper $trackerKeeper) {
        $this->_trackerKeeper = $trackerKeeper;
    }

    public function search(Requests\Tracker\Search $request) {
        throw new \Exception('123');
        return $this->_trackerKeeper
            ->getTrackerById($request->tracker_id)
            ->search($request->search_query, $request->offset);
    }

    public function media(Requests\Tracker\Media $request)
    {
        $mediaId = $request->id;
        $media = Media::find($mediaId);

        return $this->_trackerKeeper
            ->getTrackerById($media->tracker_id)
            ->loadMediaById($mediaId);
    }

    public function download(Requests\Tracker\Download $request)
    {
        $torrentId = $request->id;
        $torrent = Torrent::find($torrentId);

        $this->_trackerKeeper
            ->getTrackerById($torrent->media->tracker_id)
            ->startDownload($torrent);

        return response()->json(['status' => 'success']);
    }
}
