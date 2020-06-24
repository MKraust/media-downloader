<?php

namespace App\Tracker\Anidub;

use App\Models;
use App\Tracker;
use Illuminate\Support\Collection;

class Engine extends Tracker\BaseTracker
{
    public function id(): string
    {
        return 'anidub';
    }

    public function title(): string
    {
        return 'AniDub';
    }

    public function icon(): string
    {
        return asset('/media/tracker/anidub.png');
    }

    /**
     * @param string $query
     * @return Collection|Models\Media[]
     */
    public function search(string $query, int $offset): Collection
    {
        if ($offset % 15 > 0) {
            return collect();
        }
        $html = (new Requester())->search($query, $offset);
        $parser = new SearchResultsParser();
        $itemsData = $parser->parse($html);

        return $itemsData->map(function (array $itemData) {
            return $this->createMediaFromData($itemData);
        });
    }

    protected function loadTorrentFile(string $url): string {
        return (new Requester())->loadTorrentFile($url);
    }

    protected function loadMediaByUrl(string $url): ?Models\Media
    {
        $html = (new Requester())->loadMediaPage($url);
        $parser = new MediaPageParser;
        $itemData = $parser->parse($html);
        $itemData['url'] = $url;

        return $this->createMediaFromData($itemData);
    }
}
