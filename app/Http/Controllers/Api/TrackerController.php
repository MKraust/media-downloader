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

    public function __construct(Requests\TrackerRequest $request, Tracker\Keeper $trackerKeeper)
    {
        $trackerId = $request->tracker;
        $this->_tracker = $trackerKeeper->getTrackerById($trackerId);
    }

    public function search(Requests\Tracker\Search $request): Collection
    {
        return $this->_tracker->search(request('query'), $request->offset);
    }

    public function media(Requests\Tracker\Media $request): array
    {
        return $this->_tracker->loadMediaById($request->id)->jsonSerialize();
    }

    public function download(Requests\Tracker\Download $request)
    {
        $this->_tracker->startDownload($request->url, $request->type);

        return response()->json(['status' => 'success']);
    }
}
