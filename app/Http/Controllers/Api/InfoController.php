<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tracker;
use Illuminate\Support\Collection;
use App\Server;

class InfoController extends Controller
{
    /** @var Tracker\Keeper */
    private $_trackerKeeper;

    /** @var Server\Storage */
    private $_serverStorage;

    public function __construct(Tracker\Keeper $trackerKeeper, Server\Storage $serverStorage)
    {
        $this->_trackerKeeper = $trackerKeeper;
        $this->_serverStorage = $serverStorage;
    }

    public function trackers(): Collection {
        return $this->_trackerKeeper->getTrackers()->map(static function (Tracker\BaseTracker $tracker) {
            return $tracker->serialize();
        });
    }

    public function storage(): array {
        return $this->_serverStorage->getAvailableSpace();
    }
}
