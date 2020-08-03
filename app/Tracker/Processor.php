<?php

namespace App\Tracker;

use App\Models\Media;
use App\Telegram;

class Processor {

    /** @var Keeper */
    private $_keeper;

    /** @var Telegram\Client */
    private $_telegram;

    public function __construct(Keeper $keeper, Telegram\Client $telegram) {
        $this->_keeper = $keeper;
        $this->_telegram = $telegram;
    }

    public function checkFavoritesForNewEpisodes(): void {
        $favorites = Media::favorite()->with('torrents')->get();

        $mediaTitles = collect();
        foreach ($favorites as $favorite) {
            $topSeason = $favorite->topSeason();
            $tracker = $this->_keeper->getTrackerById($favorite->tracker_id);

            $updatedMedia = $tracker->loadMediaById($favorite->id);
            if ($updatedMedia === null) {
                continue;
            }

            $updatedTopSeason = $updatedMedia->topSeason();
            if ($this->_hasNewEpisodes($topSeason, $updatedTopSeason)) {
                $mediaTitle = $this->_getMediaTitleForNotification($updatedMedia);
                $mediaTitles->push($mediaTitle);
            }
        }

        if ($mediaTitles->count() > 0) {
            $this->_telegram->notifyAboutNewEpisodes($mediaTitles->sort());
        }
    }

    private function _hasNewEpisodes(array $previousSeason, array $newSeason): bool {
        return $newSeason !== null && ($newSeason[0] > $previousSeason[0] || $newSeason[1] > $previousSeason[1]);
    }

    private function _getMediaTitleForNotification(Media $media): string {
        $season = $media->topSeason();
        $tracker = $this->_keeper->getTrackerById($media->tracker_id);

        $seasonInfo = $season[0] === 1
            ? "({$season[1]} серия)"
            : "({$season[0]} сезон, {$season[1]} серия)";

        return "\[{$tracker->title()}] {$media->title} {$seasonInfo}";
    }
}