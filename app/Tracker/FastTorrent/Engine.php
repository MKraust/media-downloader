<?php

namespace App\Tracker\FastTorrent;

use App\Tracker;
use App\Models;
use Illuminate\Support\Collection;

class Engine extends Tracker\BaseTracker implements Tracker\BlockedTracker
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
        return 'http://media7.veleto.ru/media/uploads/logo/favicon.ico';
    }

    public function getSearchingUrl(string $query): string {
        return (new Requester)->getSearchUrl($query);
    }

    public function parseSearchResults(string $html): Collection {
        return $this->parseSearchResultsHtml($html);
    }

    public function getMediaUrls(string $mediaId): array {
        $url = $this->decryptUrl($mediaId);

        return [
            'media'    => $url,
            'torrents' => (new Requester)->getTorrentsUrlByMediaUrl($url),
        ];
    }

    public function parseMedia(string $mediaId, array $htmlParts): Models\Media {
        $mediaPageParser = new MediaPageParser;
        $itemData = $mediaPageParser->parse($htmlParts['media']);

        $torrentsParser = new TorrentsParser;
        $itemData['torrents'] = $torrentsParser->parse($htmlParts['torrents']);

        $itemData['url'] = $this->decryptUrl($mediaId);

        return $this->createMediaFromData($itemData);
    }

    public function search(string $query, int $offset): Collection {
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
