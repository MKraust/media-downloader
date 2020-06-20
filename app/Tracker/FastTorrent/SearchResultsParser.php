<?php

namespace App\Tracker\FastTorrent;

use App\Tracker\BaseSearchResultsParser;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultsParser extends BaseSearchResultsParser
{
    protected function getMediaItemsNodes(Crawler $document) {
        return $document->filter('.film-list .film-item .film-image');
    }

    protected function getTitle(Crawler $mediaNode): string {
        return $mediaNode->attr('alt');
    }

    protected function getOriginalTitle(Crawler $mediaNode): ?string {
        return null;
    }

    protected function getLink(Crawler $mediaNode): string {
        $relativeUrl = $mediaNode->children()->eq(0)->attr('href');
        return Requester::BASE_URL . $relativeUrl;
    }

    protected function getPoster(Crawler $mediaNode): string {
        $linkStyle = $mediaNode->children()->eq(0)->attr('style');
        return str_replace(['background: url(', ')', '/cache', '_video_list'], '', $linkStyle);
    }
}
