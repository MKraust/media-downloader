<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $torrent_id
 * @property \DateTime $finished_at
 * @property string $path
 * @property-read Torrent $torrent
 */
class FinishedDownload extends Model
{
    public $timestamps = false;

    protected $casts = [
        'finished_at' => 'datetime',
    ];

    public function torrent() {
        return $this->belongsTo(Torrent::class);
    }
}
