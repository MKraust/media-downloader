<?php

namespace App\Tracker;

use App\Exceptions\TrackerNotFound;
use Illuminate\Support\Collection;

class Keeper
{
    /**
     * @return Collection
     */
    public function getTrackers(): Collection {
        return collect([
            new Anidub\Engine,
            new Animedia\Engine,
            new FastTorrent\Engine,
        ]);
    }

    public function getTrackerById(string $id): BaseTracker {
        $tracker = $this->getTrackers()->first(static function (BaseTracker $tracker) use ($id) {
            return $tracker->id() === $id;
        });

        if ($tracker === null) {
            throw new TrackerNotFound("Tracker not found: {$id}");
        }

        return $tracker;
    }

    /**
     * @return string[]
     */
    public function getTrackerIds(): array {
        return $this->getTrackers()->map(static function (BaseTracker $tracker) {
            return $tracker->id();
        })->toArray();
    }
}
