<?php

namespace App\Tracker;

class Media implements \JsonSerializable {

    public $id;

    public $trackerId;

    public $url;

    public $title;

    public $originalTitle;

    public $seriesCount;

    public $poster;

    public $torrents = [];

    public $isFavored = false;

    public function jsonSerialize(): array
    {
        return [
            'id'             => $this->id,
            'tracker_id'     => $this->trackerId,
            'title'          => $this->title,
            'original_title' => $this->originalTitle,
            'series_count'   => $this->seriesCount,
            'poster'         => $this->poster,
            'torrents'       => $this->torrents,
            'is_favored'     => $this->isFavored,
        ];
    }
}
