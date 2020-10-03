<?php

namespace App\Tracker\Anidub;

use App\Exceptions\MediaParsingException;
use App\Models\Torrent;
use Symfony\Component\DomCrawler\Crawler;

class MediaPageParser extends TitleParser
{
    /** @var TitleParser */
    private $_titleParser;

    public function __construct() {
        $this->_titleParser = new TitleParser;
    }

    public function parse(string $html): array {
        $crawler = new Crawler($html);

        $media = [];

        $itemNode = $crawler->filter('#dle-content .story')->first();
        $fullTitle = $this->parseFullTitle($itemNode);

        $media['title'] = $this->_titleParser->parseTitle($fullTitle);
        $media['original_title'] = $this->_titleParser->parseOriginalTitle($fullTitle);
        $media['series_count'] = $this->_titleParser->parseSeriesCount($fullTitle);
        $media['poster'] = $itemNode->filter('.poster > img')->first()->attr('src');

        $season = $media['series_count'] ? $this->getSeasonBySeriesCount($media['series_count']) : null;
        $contentType = $this->getContentTypeByFullTitle($fullTitle);

        $torrents = collect();
        $staticH = $crawler->filter('#tabs > .static_h')->first();
        $torrentTabLinks = $staticH->filter('ul > li > a');
        $voiceActing = $this->parseVoiceActing($itemNode);
        $torrentTabLinks->each(static function (Crawler $torrentTabLink) use ($crawler, $torrents, $voiceActing, $contentType, $season) {
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
            $torrent['content_type'] = $contentType;
            $torrent['season']       = $season;

            $torrents->add($torrent);
        });

        $media['torrents'] = $torrents;

        return $media;
    }

    private function getContentTypeByFullTitle(string $title): string {
        return preg_match('/\d+ из \d+/u', $title)
            ? Torrent::TYPE_ANIME
            : Torrent::TYPE_MOVIE;
    }

    private function parseVoiceActing(Crawler $itemNode): ?string {
        $info = $itemNode->filter('.xfinfodata')->first();

        $voiceActing = null;
        $info->children('b')->each(static function (Crawler $node) use (&$voiceActing) {
           if (mb_strpos($node->text(), 'Озвучивание') !== false) {
               $voiceActing = $node->nextAll()->text();
           }
        });

        return $voiceActing;
    }

    private function parseFullTitle(Crawler $itemNode): string {
        return $itemNode->filter('.story_h #news-title')->text();
    }

    private function getSeasonBySeriesCount(string $seriesCount): array {
        preg_match('/\d+(\s*)из(\s*)\d+/ui', $seriesCount, $matches);
        if (count($matches) === 0) {
            throw new MediaParsingException('Can not parse season from series count: ' . $seriesCount);
        }

        $series = (int)array_map('trim', explode('из', $matches[0]))[0];

        return [1, $series];
    }
}
