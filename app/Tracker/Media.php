<?php

namespace App\Tracker;

class Media implements \JsonSerializable {

    public $id;

    public $title;

    public $originalTitle;

    public $seriesCount;

    public $poster;

    public $torrents = [];

    public function jsonSerialize(): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'original_title' => $this->originalTitle,
            'series_count'   => $this->seriesCount,
            'poster'         => $this->poster,
            'torrents'       => $this->torrents,
        ];
    }
}
