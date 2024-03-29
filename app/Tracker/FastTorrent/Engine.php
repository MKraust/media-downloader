<?php

namespace App\Tracker\FastTorrent;

use App\Tracker;
use App\Models;
use Illuminate\Support\Collection;

class Engine extends Tracker\BaseTracker
{
    public function id(): string
    {
        return 'fast_torrent';
    }

    public function title(): string
    {
        return 'Fast Torrent';
    }

    public function icon(): string {
        return asset('/media/tracker/fast-torrent.ico');
    }

    public function search(string $query, int $offset): Collection {
        if ($offset > 0) {
            return collect();
        }

        $html = (new Requester)->search($query);
        return $this->parseSearchResultsHtml($html);
    }

    private function parseSearchResultsHtml(string $html): Collection {
        $mediaItemsData = (new SearchResultsParser)->parse($html);

        return $mediaItemsData->map(function (array $mediaItemData) {
            return $this->createMediaFromData($mediaItemData);
        });
    }

    protected function loadTorrentFile(string $url): string
    {
        return (new Requester())->loadTorrentFile($url);
    }

    protected function loadMediaByUrl(string $url): ?Models\Media
    {
        $requester = new Requester;

        $mediaHtml = $requester->loadMediaPage($url);
        $mediaPageParser = new MediaPageParser;
        $itemData = $mediaPageParser->parse($mediaHtml);

        $torrentsHtml = $requester->loadTorrentsHtml($url);
        $torrentsParser = new TorrentsParser;
        $itemData['torrents'] = $torrentsParser->parse($torrentsHtml);

        $itemData['url'] = $url;

        return $this->createMediaFromData($itemData);
    }
}
