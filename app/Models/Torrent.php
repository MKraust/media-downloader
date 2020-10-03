<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read  Media $media
 */
class Torrent extends Model
{
    public const TYPE_MOVIE = 'movie';
    public const TYPE_SERIES = 'series';
    public const TYPE_ANIME = 'anime';

    private const DOWNLOAD_DIRECTORIES_BY_TYPE = [
        self::TYPE_MOVIE  => 'movies',
        self::TYPE_SERIES => 'series',
        self::TYPE_ANIME  => 'anime',
    ];

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

    public function getSeasonAttribute(): array {
        return json_decode($this->attributes['season'], true);
    }

    public function setSeasonAttribute(?array $value): void {
        $this->attributes['season'] = $value !== null ? json_encode($value) : null;
    }

    public function getDownloadPathAttribute(): ?string {
        $downloadDirectory = $this->_getDownloadDirectory();
        return $this->attributes['download_path'] !== null ? $downloadDirectory . $this->attributes['download_path'] : null;
    }

    public function setDownloadPathAttribute(string $fullPath): void {
        $directory = $this->_getDownloadDirectory();
        $this->attributes['download_path'] = str_replace($directory, '', $fullPath);
    }

    public function isDownloaded(): bool {
        return $this->download_path !== null;
    }

    private function _getDownloadDirectory(): string {
        $type = $this->content_type;
        $directory = self::DOWNLOAD_DIRECTORIES_BY_TYPE[$type];
        return "/downloads/{$directory}/";
    }
}
