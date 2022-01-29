<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $content_type
 */
class Torrent extends Model
{
    public const TYPE_MOVIE = 'movie';
    public const TYPE_SERIES = 'series';
    public const TYPE_ANIME = 'anime';

    protected $fillable = [
        'name',
        'url',
        'content_type',
        'voice_acting',
        'quality',
        'size',
        'size_int',
        'downloads',
        'season',
    ];

    public function media() {
        return $this->belongsTo(Media::class);
    }

    public function download() {
        return $this->hasOne(TorrentDownload::class);
    }

    public function getSeasonAttribute() {
        return json_decode($this->attributes['season'], true);
    }

    public function setSeasonAttribute(?array $value) {
        $this->attributes['season'] = $value !== null ? json_encode($value) : null;
    }
}
