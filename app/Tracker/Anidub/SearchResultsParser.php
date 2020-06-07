<?php

namespace App\Tracker\Anidub;

use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultsParser
{
    public function parse(string $html): Collection {
        $crawler = new Crawler($html);
        $searchItemNodes = $crawler->filter('.search_post');

        $items = collect();
        $searchItemNodes->each(static function (Crawler $node) use ($items) {
            $titleNode = $node->filter('.text > h2 > a')->first();
            $title = $titleNode->text();
            $url = $titleNode->attr('href');
            $poster = $node->filter('.poster > img')->first()->attr('src');

            $items->add([
                'url'    => $url,
                'title'  => $title,
                'poster' => $poster,
            ]);
        });

        return $items;
    }
}
