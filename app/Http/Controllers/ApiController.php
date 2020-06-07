<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tracker;
use Illuminate\Support\Collection;

class ApiController extends Controller
{
    public function trackers(Tracker\Keeper $trackerKeeper): Collection {
        return $trackerKeeper->getTrackers()->map(static function (Tracker\Base $tracker) {
            return $tracker->serialize();
        });
    }

    public function search(Tracker\Keeper $trackerKeeper): Collection {
        $trackerId = request('tracker');
        $searchQuery = request('query');

        $tracker = $trackerKeeper->getTrackerById($trackerId);
        // TODO: throw not found exception

        return $tracker->search($searchQuery);
    }

    public function media(Tracker\Keeper $trackerKeeper): array {
        $id = request('id');
        $trackerId = request('tracker');

        $tracker = $trackerKeeper->getTrackerById($trackerId);
        $media = $tracker->loadMediaById($id);

        return [
            'id' => $media->id,
            'title' => $media->title,
            'poster' => $media->poster,
            'torrents' => $media->torrents,
        ];
    }

    public function download(Tracker\Keeper $trackerKeeper) {
        $trackerId = request('tracker');
        $url = request('url');
        $contentType = request('type');

        $tracker = $trackerKeeper->getTrackerById($trackerId);
        $tracker->startDownload($url, $contentType);

        return response()->json(['status' => 'success']);
    }
}
