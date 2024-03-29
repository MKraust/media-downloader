<?php

namespace App\Tracker\FastTorrent;

use Symfony\Component\DomCrawler\Crawler;

class MediaPageParser
{
    public function parse(string $html): array {
        $page = new Crawler($html);

        $infoNode = $page->filter('.film-info1')->first();
        $media['poster'] = $infoNode->filter('.info-panel > .film-image > a')->first()->attr('href');
        $media['title'] = $page->filter('#film_name')->first()->text();

        $originalTitleNodes = $page->filter('#film_name_orig');
        if (count($originalTitleNodes) > 0) {
            $media['original_title'] = $originalTitleNodes->first()->text();
        }

        return $media;
    }
}
