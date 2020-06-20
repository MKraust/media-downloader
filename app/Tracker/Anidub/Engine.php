<?php

namespace App\Tracker\Anidub;

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
     * @return Collection|Tracker\Media[]
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

    protected function loadMediaByUrl(string $url): ?Tracker\Media
    {
        $html = (new Requester())->loadMediaPage($url);
        $parser = new MediaPageParser;
        $itemData = $parser->parse($html);
        $itemData['url'] = $url;

        return $this->createMediaFromData($itemData);
    }

    protected function prepareMediaData(array $data): array
    {
        $preparedData = $data;

        $title = $data['title'] ?? null;
        if ($title !== null) {
            $titleParts = explode('/ ', $data['title']); // fix this as in titles may be slashes
            $trimmedTitleParts = array_map('trim', $titleParts);
            $preparedData['title'] = $trimmedTitleParts[0];
            $preparedData['original_title'] = trim(preg_replace('/\[.+]/u', '', $trimmedTitleParts[1]));

            preg_match('/\[.+]/u', $title, $seriesCount);
            $seriesCount = $seriesCount[0] ?? null;
            if ($seriesCount !== null) {
                $preparedData['series_count'] = str_replace(['[', ']'], '', $seriesCount);
            }
        }

        return $preparedData;
    }
}
