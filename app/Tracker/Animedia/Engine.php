<?php


namespace App\Tracker\Animedia;

use App\Tracker;
use App\Tracker\Media;
use Illuminate\Support\Collection;

class Engine extends Tracker\BaseTracker
{

    public function id(): string
    {
        return 'animedia';
    }

    public function title(): string
    {
        return 'AniMedia';
    }

    public function icon(): string
    {
        return 'https://tt.animedia.tv/favicon.ico';
    }

    public function search(string $query, int $offset): Collection
    {
        if ($offset % 40 > 0) {
            return collect();
        }

        $html = (new Requester())->search($query, $offset);
        $parser = new SearchResultsParser();
        $itemsData = $parser->parse($html);

        return $itemsData->map(function (array $itemData) {
            return $this->createMediaFromData($itemData);
        });
    }

    protected function loadTorrentFile(string $url): string
    {
        return (new Requester())->loadTorrentFile($url);
    }

    protected function loadMediaByUrl(string $url): ?Media
    {
        return new Media;
    }

    protected function prepareMediaData(array $data): array
    {
        $preparedData = $data;

        $poster = $data['poster'] ?? null;
        if ($poster !== null) {
            $posterUrlWithoutQuery = explode('?', $poster)[0];
            $preparedData['poster'] = $posterUrlWithoutQuery . '?h=770&w=560&q=100';
        }

        return $preparedData;
    }
}
