<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent;

class TorrentDownload extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'hash';

    protected $fillable = [
        'hash',
        'name',
    ];

    public function scopeActive(Eloquent\Builder $query): Eloquent\Builder {
        return $query->where('is_deleted', 0);
    }
}
