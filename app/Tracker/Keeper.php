<?php

namespace App\Tracker;

use Illuminate\Support\Collection;

class Keeper
{
    /**
     * @return Collection
     */
    public function getTrackers(): Collection {
        return collect([
            new Anidub\Engine,
//            new Animedia\Engine,
            new FastTorrent\Engine,
        ]);
    }

    public function getTrackerById(string $id): ?BaseTracker {
        return $this->getTrackers()->first(static function (BaseTracker $tracker) use ($id) {
            return $tracker->id() === $id;
        });
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
