<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Http\Requests;

class FavoritesController extends Controller
{

    public function addToFavorites(Requests\Favorites\Manage $request) {
        $mediaId = $request->id;

        $media = Media::find($mediaId);
        $media->is_favorite = true;
        $media->added_to_favorites_at = now();
        $media->save();

        return response()->json('success');
    }

    public function removeFromFavorites(Requests\Favorites\Manage $request) {
        $mediaId = $request->id;

        $media = Media::find($mediaId);
        $media->is_favorite = false;
        $media->added_to_favorites_at = null;
        $media->save();

        return response()->json('success');
    }

    public function getFavorites() {
        return Media::favorite()->get();
    }
}
