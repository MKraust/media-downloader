<?php

namespace App\Tracker\Anidub;

use Symfony\Component\DomCrawler\Crawler;

class MediaPageParser
{
    public function parse(string $html): array {
        $crawler = new Crawler($html);

        $result = [];

        $itemNode = $crawler->filter('#dle-content .story')->first();
        $result['title'] = $itemNode->filter('.story_h #news-title')->text();
        $result['poster'] = $itemNode->filter('.poster > img')->first()->attr('src');

        $torrents = collect();
        $staticH = $crawler->filter('#tabs > .static_h')->first();
        $torrentTabLinks = $staticH->filter('ul > li > a');
        $voiceActing = $this->parseVoiceActing($itemNode);
        $torrentTabLinks->each(static function (Crawler $torrentTabLink) use ($crawler, $torrents, $voiceActing) {
            $torrent = [];

            $tabId = $torrentTabLink->attr('href');
            $torrent['quality'] = $torrentTabLink->text();

            $tab = $crawler->filter($tabId)->first();
            $relativeTorrentLink = $tab->filter('.torrent_h a')->first()->attr('href');
            $torrent['url'] = Requester::BASE_URL . $relativeTorrentLink;
            $torrent['name'] = $torrent['quality'];

            $torrent['size'] = $tab->filter('.list.down .red')->first()->text();
            $torrent['downloads'] = (int)$tab->filter('.list.down .li_download_m')->first()->text();

            $torrent['voice_acting'] = $voiceActing;
            $torrents->add($torrent);
        });

        $result['torrents'] = $torrents;

        return $result;
    }

    private function parseVoiceActing(Crawler $itemNode): string {
        $info = $itemNode->filter('.xfinfodata')->first();

        $voiceActing = null;
        $info->children('b')->each(static function (Crawler $node) use (&$voiceActing) {
           if (mb_strpos($node->text(), 'Перевод') !== false) {
               $voiceActing = $node->nextAll()->text();
           }
        });

        return $voiceActing;
    }
}