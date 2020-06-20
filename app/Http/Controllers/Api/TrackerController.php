<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tracker;
use Illuminate\Support\Collection;

class TrackerController extends Controller
{
    /** @var Tracker\Base */
    private $_tracker;

    public function __construct(Tracker\Keeper $trackerKeeper)
    {
        $trackerId = request('tracker');
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
