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

        $mediaWithNewEpisodes = collect();
        foreach ($favorites as $favorite) {
            $topSeason = $favorite->topSeason();
            $tracker = $this->_keeper->getTrackerById($favorite->tracker_id);

            $updatedMedia = $tracker->loadMediaById($favorite->id);
            if ($updatedMedia === null) {
                continue;
            }

            $updatedTopSeason = $updatedMedia->topSeason();
            if ($updatedTopSeason !== null && ($updatedTopSeason[0] > $topSeason[0] || $updatedTopSeason[1] > $topSeason[1])) {
                $mediaWithNewEpisodes->push($updatedMedia);
            }
        }

        if ($mediaWithNewEpisodes->count() === 0) {
            return;
        }

        $mediaTitles = $mediaWithNewEpisodes->map(function (Media $media) {
            $tracker = $this->_keeper->getTrackerById($media->tracker_id);
            return "\[{$tracker->title()}] {$media->title}";
        })->sort();

        $this->_telegram->notifyAboutNewEpisodes($mediaTitles);
    }
}