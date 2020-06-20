<?php


namespace App\Tracker\Animedia;

use App\Tracker;
use App\Tracker\Media;
use Illuminate\Support\Collection;

class Engine extends Tracker\Base
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
        return collect();
//        $mediaListJson = (new Requester())->getMediaList();
//        $mediaList = collect(json_decode($mediaListJson, true));
//        $mediaItems = $this->findMedia($mediaList, $query);
//
//        return $mediaItems->map(function (array $itemData) {
//            return $this->createMediaFromData($itemData);
//        });
    }

    protected function loadTorrentFile(string $url): string
    {
        // TODO: Implement loadTorrentFile() method.
    }

    protected function loadMediaByUrl(string $url): ?Media
    {
        return new Media;
    }
}
