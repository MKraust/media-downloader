<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent;

/**
 * @property string $hash
 * @property bool $is_deleted
 * @property bool $is_finished
 */
class TorrentDownload extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'hash';

    protected $fillable = [
        'hash',
        'torrent_id',
    ];

    public function torrent() {
        return $this->belongsTo(Torrent::class);
    }

    public function scopeActive(Eloquent\Builder $query): Eloquent\Builder {
        return $query->where('is_deleted', 0)->where('is_finished', 0);
    }
}
