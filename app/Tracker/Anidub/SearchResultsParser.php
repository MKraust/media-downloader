<?php

namespace App\Tracker\Anidub;

use App\Tracker\BaseSearchResultsParser;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultsParser extends BaseSearchResultsParser
{
    /** @var TitleParser */
    private $_titleParser;

    public function __construct() {
        $this->_titleParser = new TitleParser;
    }

    protected function getMediaItemsNodes(Crawler $document) {
        return $document->filter('.search_post');
    }

    protected function getTitle(Crawler $mediaNode): string {
        $fullTitle = $this->getTitleNode($mediaNode)->text();

        return $this->_titleParser->parseTitle($fullTitle);
    }

    protected function getOriginalTitle(Crawler $mediaNode): ?string {
        $fullTitle = $this->getTitleNode($mediaNode)->text();

        return $this->_titleParser->parseOriginalTitle($fullTitle);
    }

    protected function getSeriesCount(Crawler $mediaNode): ?string {
        $fullTitle = $this->getTitleNode($mediaNode)->text();

        return $this->_titleParser->parseSeriesCount($fullTitle);
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
