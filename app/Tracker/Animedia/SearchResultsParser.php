<?php

namespace App\Tracker\Animedia;

use App\Tracker\BaseSearchResultsParser;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultsParser extends BaseSearchResultsParser
{
    protected function getMediaItemsNodes(Crawler $document) {
        return $document->filter('.ads-list__item');
    }

    protected function getTitle(Crawler $mediaNode): string {
        return $this->getTitleNode($mediaNode)->text();
    }

    protected function getOriginalTitle(Crawler $mediaNode): ?string {
        return $mediaNode->filter('.original-title')->first()->text();
    }

    protected function getLink(Crawler $mediaNode): string {
        return $this->getTitleNode($mediaNode)->attr('href');
    }

    protected function getPoster(Crawler $mediaNode): string {
        return $mediaNode->filter('.ads-list__item__thumb a img')->first()->attr('src');
    }

    private function getTitleNode(Crawler $mediaNode): Crawler {
        return $mediaNode->filter('.ads-list__item__title')->first();
    }
}
