<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TorrentDownload extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'hash';

    protected $fillable = [
        'hash',
        'name',
    ];
}
