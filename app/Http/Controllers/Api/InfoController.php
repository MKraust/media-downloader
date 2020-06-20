<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tracker;
use Illuminate\Support\Collection;

class InfoController extends Controller
{
    /** @var Tracker\Keeper */
    private $_trackerKeeper;

    public function __construct(Tracker\Keeper $trackerKeeper)
    {
        $this->_trackerKeeper = $trackerKeeper;
    }

    public function trackers(): Collection {
        return $this->_trackerKeeper->getTrackers()->map(static function (Tracker\BaseTracker $tracker) {
            return $tracker->serialize();
        });
    }
}
