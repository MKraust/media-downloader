<?php

namespace App\Tracker\FastTorrent;

use App\Tracker\BaseSearchResultsParser;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultsParser extends BaseSearchResultsParser
{
    protected function getMediaItemsNodes(Crawler $document) {
        return $document->filter('.film-list .film-item');
    }

    protected function getTitle(Crawler $mediaNode): string {
        return $mediaNode->filter('.film-image')->first()->attr('alt');
    }

    protected function getOriginalTitle(Crawler $mediaNode): ?string {
        $headingNodes = $mediaNode->filter('.film-wrap > h2 > span');
        $originalTitle = null;
        $headingNodes->each(function (Crawler $headingNode) use (&$originalTitle) {
            if ($headingNode->attr('itemprop') === 'alternativeHeadline') {
                $originalTitle = $headingNode->text();
            }
        });

        return $originalTitle;
    }

    protected function getLink(Crawler $mediaNode): string {
        $relativeUrl = $mediaNode->filter('.film-image')->first()->children()->eq(0)->attr('href');
        return Requester::BASE_URL . $relativeUrl;
    }

    protected function getPoster(Crawler $mediaNode): string {
        $linkStyle = $mediaNode->filter('.film-image')->first()->children()->eq(0)->attr('style');
        return str_replace(['background: url(', ')', '/cache', '_video_list'], '', $linkStyle);
    }
}
