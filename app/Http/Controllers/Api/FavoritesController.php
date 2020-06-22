<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteMedia;
use App\Models\Media;
use App\Tracker;

class FavoritesController extends Controller
{
    /** @var Tracker\Keeper */
    private $_trackerKeeper;

    public function __construct(Tracker\Keeper $trackerKeeper) {
        $this->_trackerKeeper = $trackerKeeper;
    }

    public function addToFavorites(Tracker\Keeper $trackerKeeper) {
        $mediaId = request('id');

        $media = Media::find($mediaId);
        $media->is_favorite = true;
        $media->save();

        return response()->json('success');
    }

    public function removeFromFavorites(Tracker\Keeper $trackerKeeper) {
        $mediaId = request('id');

        $media = Media::find($mediaId);
        $media->is_favorite = false;
        $media->save();

        return response()->json('success');
    }

    public function getFavorites() {
        $favorites = Media::where('is_favorite', 1)->orderByDesc('updated_at')->get();

        return $favorites;
    }
}
