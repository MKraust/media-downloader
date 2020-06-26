<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'url',
        'tracker_id',
        'title',
        'original_title',
        'series_count',
        'poster',
        'is_favorite',
        'added_to_favorites_at',
    ];

    protected $casts = [
        'added_to_favorites_at' => 'datetime',
    ];

    public function torrents() {
        return $this->hasMany(Torrent::class);
    }

    public function scopeFavorite($builder) {
        return $builder->where('is_favorite', 1)->orderByDesc('added_to_favorites_at');
    }

    public function topSeason(): array {
        $topSeason = null;
        foreach ($this->torrents as $torrent) {
            $season = $torrent->season;
            if ($season === null) {
                continue;
            }

            if ($topSeason === null || $season[0] > $topSeason[0] || $season[1] > $topSeason[1]) {
                $topSeason = $season;
            }
        }

        return $topSeason;
    }
}
