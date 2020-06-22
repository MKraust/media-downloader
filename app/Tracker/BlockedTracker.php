<?php

namespace App\Tracker;

use Illuminate\Support\Collection;
use App\Models;

interface BlockedTracker {

    public function getSearchingUrl(string $query): string;

    public function parseSearchResults(string $html): Collection;

    public function getMediaUrls(string $mediaId): array;

    public function parseMedia(string $mediaId, array $htmlParts): Models\Media;
}