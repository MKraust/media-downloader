<?php

namespace App\Tracker;

use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseSearchResultsParser
{
    final public function parse(string $html): Collection {
        $document = new Crawler($html);

        $searchItemNodes = $this->getMediaItemsNodes($document);

        $items = collect();
        $searchItemNodes->each(function (Crawler $node) use ($items) {
            $items->add([
                'url'            => $this->getLink($node),
                'title'          => $this->getTitle($node),
                'poster'         => $this->getPoster($node),
                'original_title' => $this->getOriginalTitle($node),
                'series_count'   => $this->getSeriesCount($node),
            ]);
        });

        return $items;
    }

    protected function getSeriesCount(Crawler $mediaNode): ?string {
        return null;
    }

    abstract protected function getMediaItemsNodes(Crawler $document);

    abstract protected function getTitle(Crawler $mediaNode): string;

    abstract protected function getOriginalTitle(Crawler $mediaNode): ?string;

    abstract protected function getLink(Crawler $mediaNode): string;

    abstract protected function getPoster(Crawler $mediaNode): string;
}