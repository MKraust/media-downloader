<?php

namespace App\Tracker\Animedia;

use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultsParser
{
    public function parse(string $html): Collection {
        $crawler = new Crawler($html);
        $searchItemNodes = $crawler->filter('.ads-list__item');

        $items = collect();
        $searchItemNodes->each(static function (Crawler $node) use ($items) {
            $titleNode = $node->filter('.ads-list__item__title')->first();
            $title = $titleNode->text();
            $originalTitle = $node->filter('.original-title')->first()->text();
            $url = $titleNode->attr('href');
            $poster = $node->filter('.ads-list__item__thumb a img')->first()->attr('src');

            $items->add([
                'url'            => $url,
                'title'          => $title,
                'poster'         => $poster,
                'original_title' => $originalTitle,
            ]);
        });

        return $items;
    }
}
