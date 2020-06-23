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
        // добавить дату, когда было добавлено в избранное
        $media->save();

        return response()->json('success');
    }

    public function removeFromFavorites(Requests\Favorites\Manage $request) {
        $mediaId = $request->id;

        $media = Media::find($mediaId);
        $media->is_favorite = false;
        // очищать дату добавления в избранное
        $media->save();

        return response()->json('success');
    }

    public function getFavorites() {
        return Media::where('is_favorite', 1)->orderByDesc('updated_at')->get();
    }
}
