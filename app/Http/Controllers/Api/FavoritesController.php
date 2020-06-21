<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteMedia;
use App\Tracker;

class FavoritesController extends Controller
{
    /** @var Tracker\Keeper */
    private $_trackerKeeper;

    public function __construct(Tracker\Keeper $trackerKeeper) {
        $this->_trackerKeeper = $trackerKeeper;
    }

    public function addToFavorites(Tracker\Keeper $trackerKeeper) {
        $trackerId = request('tracker');
        $mediaId = request('id');
        $title = request('title');
        $originalTitle = request('original_title');
        $poster = request('poster');

        $tracker = $trackerKeeper->getTrackerById($trackerId);
        $favoriteMedia = new FavoriteMedia([
            'tracker_id' => $trackerId,
            'url' => $tracker->decryptUrl($mediaId),
            'title' => $title,
            'original_title' => $originalTitle,
            'image_url' => $poster,
        ]);

        $favoriteMedia->save();

        return response()->json('success');
    }

    public function removeFromFavorites(Tracker\Keeper $trackerKeeper) {
        $trackerId = request('tracker');
        $mediaId = request('id');

        $tracker = $trackerKeeper->getTrackerById($trackerId);
        $url = $tracker->decryptUrl($mediaId);
        FavoriteMedia::where('url', $url)->delete();

        return response()->json('success');
    }

    public function getFavorites() {
        $favorites = FavoriteMedia::orderByDesc('created_at')->get();

        return $favorites->map(function (FavoriteMedia $favoriteMedia) {
            $tracker = $this->_trackerKeeper->getTrackerById($favoriteMedia->tracker_id);

            return [
                'id' => $tracker->encryptUrl($favoriteMedia->url),
                'tracker_id' => $favoriteMedia->tracker_id,
                'title' => $favoriteMedia->title,
                'original_title' => $favoriteMedia->original_title,
                'poster' => $favoriteMedia->image_url,
            ];
        });
    }
}
