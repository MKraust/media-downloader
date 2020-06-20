<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tracker;
use Illuminate\Support\Collection;
use App\Http\Requests;

class TrackerController extends Controller
{
    /** @var Tracker\BaseTracker */
    private $_tracker;

    public function __construct(Requests\TrackerRequest $trackerRequest, Tracker\Keeper $trackerKeeper)
    {
        $trackerId = $trackerRequest->tracker;
        $this->_tracker = $trackerKeeper->getTrackerById($trackerId);
    }

    public function search(): Collection
    {
        $searchQuery = request('query');
        $offset = request('offset');

        return $this->_tracker->search($searchQuery, $offset);
    }

    public function media(): array
    {
        $id = request('id');

        return $this->_tracker->loadMediaById($id)->jsonSerialize();
    }

    public function download()
    {
        $url = request('url');
        $contentType = request('type');

        $this->_tracker->startDownload($url, $contentType);

        return response()->json(['status' => 'success']);
    }
}
