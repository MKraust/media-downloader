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
    ];

    public function torrents() {
        return $this->hasMany(Torrent::class);
    }
}
