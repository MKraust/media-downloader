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

    public function icon(): string {
        return 'http://media7.veleto.ru/media/uploads/logo/favicon.ico';
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
        return (new Requester())->loadTorrentFile($url);
    }

    protected function loadMediaByUrl(string $url): ?Media
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
