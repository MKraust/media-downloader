<?php

namespace App\Tracker\FastTorrent;

use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultsParser
{
    public function parse(string $html): Collection {
        $crawler = new Crawler($html);
        $searchItemNodes = $crawler->filter('.film-list .film-item .film-image');

        $items = collect();
        $searchItemNodes->each(static function (Crawler $node) use ($items) {
            $title = $node->attr('alt');
            $relativeUrl = $node->children()->eq(0)->attr('href');
            $url = Requester::BASE_URL . $relativeUrl;
            $linkStyle = $node->children()->eq(0)->attr('style');
            $poster = str_replace(['background: url(', ')'], '', $linkStyle);

            $items->add([
                'url'    => $url,
                'title'  => $title,
                'poster' => $poster,
            ]);
        });

        return $items;
    }


//let title = try! itemNode.attr("alt")
//let relativeUrl = try! itemNode.child(0).attr("href")
//let url = FastTorrent.baseUrl + relativeUrl
//let linkStyle = try! itemNode.child(0).attr("style")
//let posterUrl = linkStyle
//.replacingOccurrences(of: "background: url(", with: "")
//.replacingOccurrences(of: ")", with: "")
}
