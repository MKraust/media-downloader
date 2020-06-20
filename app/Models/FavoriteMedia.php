<?php

namespace App\Models;

use App\Tracker\Media;
use Illuminate\Database\Eloquent\Model;

class FavoriteMedia extends Model
{
    protected $table = 'favorite_media';

    protected $fillable = [
        'url',
        'tracker_id',
        'title',
        'original_title',
        'image_url',
    ];

    public static function createFromMedia(string $trackerId, Media $media): self {
        $favoriteMedia = new self;
        $favoriteMedia->url = $media->url;
        $favoriteMedia->tracker_id = $trackerId;
        $favoriteMedia->title = $media->title;
        $favoriteMedia->original_title = $media->originalTitle;
        $favoriteMedia->image_url = $media->poster;

        return $favoriteMedia;
    }

    public static function isFavored(string $url): bool {
        return self::where('url', $url)->count() > 0;
    }
}
