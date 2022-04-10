<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

/**
 * @property int $id
 * @property int $torrent_id
 * @property \DateTime $finished_at
 * @property string $path
 * @property array $meta
 * @property boolean $is_deleted
 * @property-read Torrent $torrent
 * @property-read boolean $exists
 */
class FinishedDownload extends Model
{
    public $timestamps = false;

    protected $casts = [
        'finished_at' => 'datetime',
        'meta'        => 'array',
    ];

    protected $with = [
        'torrent.media',
    ];

    public function torrent() {
        return $this->belongsTo(Torrent::class);
    }

    public function getExistsAttribute() {
        return File::exists($this->attributes['path']);
    }
}
