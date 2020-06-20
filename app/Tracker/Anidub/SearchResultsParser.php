<?php

namespace App\Tracker\Anidub;

use App\Tracker\BaseSearchResultsParser;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultsParser extends BaseSearchResultsParser
{
    protected function getMediaItemsNodes(Crawler $document) {
        return $document->filter('.search_post');
    }

    protected function getTitle(Crawler $mediaNode): string {
        return $this->getTitleNode($mediaNode)->text();
    }

    protected function getOriginalTitle(Crawler $mediaNode): ?string {
        return null;
    }

    protected function getLink(Crawler $mediaNode): string {
        return $this->getTitleNode($mediaNode)->attr('href');
    }

    protected function getPoster(Crawler $mediaNode): string {
        return $mediaNode->filter('.poster > img')->first()->attr('src');
    }

    private function getTitleNode(Crawler $mediaNode): Crawler {
        return $mediaNode->filter('.text > h2 > a')->first();
    }
}
