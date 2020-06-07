<?php

namespace App\Tracker\Anidub;

use App\Tracker;
use Illuminate\Support\Collection;

class Engine extends Tracker\Base
{
    public function id(): string
    {
        return 'anidub';
    }

    public function title(): string
    {
        return 'AniDub';
    }

    /**
     * @param string $query
     * @return Collection|Tracker\Media[]
     */
    public function search(string $query): Collection
    {
        $html = (new Requester())->search($query);
        $parser = new SearchResultsParser();
        $itemsData = $parser->parse($html);

        return $itemsData->map(function (array $itemData) {
            return $this->createMediaFromData($itemData);
        });
    }

    protected function loadTorrentFile(string $url): string {
        return (new Requester())->loadTorrentFile($url);
    }

    protected function loadMediaByUrl(string $url): ?Tracker\Media
    {
        $html = (new Requester())->loadMediaPage($url);
        $parser = new MediaPageParser;
        $itemData = $parser->parse($html);
        $itemData['url'] = $url;

        return $this->createMediaFromData($itemData);
    }
}
