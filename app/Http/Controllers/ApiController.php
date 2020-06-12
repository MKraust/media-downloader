<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tracker;
use Illuminate\Support\Collection;

class ApiController extends Controller
{
    /** @var Tracker\Keeper */
    private $trackerKeeper;

    public function __construct(Tracker\Keeper $trackerKeeper) {
        $this->trackerKeeper = $trackerKeeper;
    }

    public function trackers(): Collection {
        return $this->trackerKeeper->getTrackers()->map(static function (Tracker\Base $tracker) {
            return $tracker->serialize();
        });
    }

    public function search(): Collection {
        $trackerId = request('tracker');
        $searchQuery = request('query');

        $tracker = $this->trackerKeeper->getTrackerById($trackerId);
        // TODO: throw not found exception

        return $tracker->search($searchQuery);
    }

    public function media(): array {
        $id = request('id');
        $trackerId = request('tracker');

        $tracker = $this->trackerKeeper->getTrackerById($trackerId);
        $media = $tracker->loadMediaById($id);

        return [
            'id' => $media->id,
            'title' => $media->title,
            'poster' => $media->poster,
            'torrents' => $media->torrents,
        ];
    }

    public function download() {
        $trackerId = request('tracker');
        $url = request('url');
        $contentType = request('type');

        $tracker = $this->trackerKeeper->getTrackerById($trackerId);
        $tracker->startDownload($url, $contentType);

        return response()->json(['status' => 'success']);
    }

    public function getSearchUrl() {
        $trackerId = request('tracker');
        $query = request('query');

        /** @var Tracker\BlockedTracker $tracker */
        $tracker = $this->trackerKeeper->getTrackerById($trackerId);

        return $tracker->getSearchingUrl($query);
    }

    public function parseSearchResult() {
        $trackerId = request('tracker');
        $html = request('html');

        /** @var Tracker\BlockedTracker $tracker */
        $tracker = $this->trackerKeeper->getTrackerById($trackerId);

        return $tracker->parseSearchResults($html);
    }

    public function getMediaUrls() {
        $trackerId = request('tracker');
        $mediaId = request('id');

        /** @var Tracker\BlockedTracker $tracker */
        $tracker = $this->trackerKeeper->getTrackerById($trackerId);

        return $tracker->getMediaUrls($mediaId);
    }

    public function parseMedia() {
        $trackerId = request('tracker');
        $mediaId = request('id');
        $htmlParts = request('html');

        /** @var Tracker\BlockedTracker $tracker */
        $tracker = $this->trackerKeeper->getTrackerById($trackerId);

        $media = $tracker->parseMedia($mediaId, $htmlParts);

        return [
            'id' => $media->id,
            'title' => $media->title,
            'poster' => $media->poster,
            'torrents' => $media->torrents,
        ];
    }

    public function downloadFromFile() {
        $trackerId = request('tracker');
        $contentType = request('type');
        $file = request('file');

        $tracker = $this->trackerKeeper->getTrackerById($trackerId);
        $tracker->startDownloadFromFile($file, $contentType);

        return response()->json(['status' => 'success']);
    }
}
