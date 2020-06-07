<?php

namespace App\Tracker\FastTorrent;

use App\Tracker;
use App\Tracker\Media;
use Illuminate\Support\Collection;

class Engine extends Tracker\Base
{

    public function id(): string
    {
        return 'fast_torrent';
    }

    public function title(): string
    {
        return 'Fast Torrent';
    }

    public function search(string $query): Collection
    {
        $html = (new Requester)->search($query);
        $mediaItemsData = (new SearchResultsParser)->parse($html);

        return $mediaItemsData->map(function (array $mediaItemData) {
            return $this->createMediaFromData($mediaItemData);
        });
    }

    protected function loadTorrentFile(string $url): string
    {
        // TODO: Implement loadTorrentFile() method.
    }

    protected function loadMediaByUrl(string $url): ?Media
    {
        // TODO: Implement loadMediaByUrl() method.
    }
}
