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

    public function getTrackerById(string $id): ?Base {
        return $this->getTrackers()->first(static function (Base $tracker) use ($id) {
            return $tracker->id() === $id;
        });
    }
}
