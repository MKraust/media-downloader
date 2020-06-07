<?php

namespace App\Tracker;

use Illuminate\Support\Collection;

abstract class Base
{
    abstract public function id(): string;

    abstract public function title(): string;

    /**
     * @param string $query
     * @return Collection|Media[]
     */
    abstract public function search(string $query): Collection;

    final public function serialize(): array {
        return [
            'id'    => $this->id(),
            'title' => $this->title(),
        ];
    }

    public function loadMediaById(string $id): ?Media {
        $decryptedUrl = $this->decryptUrl($id);
        return $this->loadMediaByUrl($decryptedUrl);
    }

    abstract protected function loadMediaByUrl(string $url): ?Media;

    final protected function encryptUrl(string $url): string {
        return encrypt($url);
    }

    final protected function decryptUrl(string $encryptedUrl): string {
        return decrypt($encryptedUrl);
    }
}
