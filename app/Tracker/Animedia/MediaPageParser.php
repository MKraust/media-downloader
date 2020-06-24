<?php

namespace App\Tracker\Animedia;

use App\Models\Torrent;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;
use App\Exceptions;

class MediaPageParser
{
    public function parse(string $html): array {
        $crawler = new Crawler($html);

        $result = [];

        $itemNode = $crawler->filter('.content-container')->first();
        $result['title'] = $itemNode->filter('.media__post__title')->text();
        $result['original_title'] = $this->_parseOriginalTitle($itemNode);
        $result['poster'] = $itemNode->filter('.widget__post-info__poster > a')->first()->attr('href');

        if (count($itemNode->filter('.media__tabs__nav > .media__tabs__nav__item > a')) > 0) {
            $result['torrents'] = $this->_parseTorrentsTabs($itemNode);
        } else {
            $result['torrents'] = $this->_parseTorrentsList($itemNode);
        }

        return $result;
    }

    private function _parseTorrentsList(Crawler $mediaNode): Collection {
        $torrents = collect();

        $torrentBlocks = $mediaNode->filter('.over__block > .input_block');
        $voiceActing = $this->_parseVoiceActing($mediaNode);
        $torrentBlocks->each(function (Crawler $torrentBlock) use ($torrents, $voiceActing) {
            $torrent = [];

            $torrent['name'] = $torrentBlock->filter('.intup_left_top')->first()->text();
            $torrent['url'] = $torrentBlock->filter('.download_tracker_btn_input')->first()->attr('href');

            $torrent['quality'] = $this->_parseQualityFromBlock($torrentBlock);
            $torrent['size'] = $this->_parseSizeFromBlock($torrentBlock);
            $torrent['downloads'] = $this->_parseDownloadsFromBlock($torrentBlock);

            $torrent['voice_acting'] = $voiceActing;
            $torrent['content_type'] = $this->_getContentTypeByTitle($torrent['name']);
            $torrents->add($torrent);
        });

        return $torrents;
    }

    private function _parseQualityFromBlock(Crawler $torrentBlock): ?string {
        $infoNode = $torrentBlock->filter('.intup_left_ser')->first();
        $infoText = $infoNode->text();
        $quality = trim(preg_replace('/\(.*\)/u', '', $infoText));

        return $quality !== '' ? $quality : null;
    }

    private function _parseSizeFromBlock(Crawler $torrentBlock) {
        $infoNodes = $torrentBlock->filter('.intup_left_op');
        foreach ($infoNodes as $infoNode) {
            if (mb_strpos($infoNode->textContent, 'Размер') !== false) {
                return trim($infoNode->childNodes[1]->textContent, ' ;');
            }
        }

        return null;
    }

    private function _parseDownloadsFromBlock(Crawler $torrentBlock) {
        $infoNodes = $torrentBlock->filter('.intup_left_op');
        foreach ($infoNodes as $infoNode) {
            if (mb_strpos($infoNode->textContent, 'Скачиваний') !== false) {
                return trim($infoNode->childNodes[1]->textContent, ' ;');
            }
        }

        return null;
    }

    private function _parseTorrentsTabs(Crawler $mediaNode): Collection {
        $torrents = collect();

        $torrentTabLinks = $mediaNode->filter('.media__tabs__nav > .media__tabs__nav__item > a');
        $voiceActing = $this->_parseVoiceActing($mediaNode);
        $torrentTabLinks->each(function (Crawler $torrentTabLink) use ($mediaNode, $torrents, $voiceActing) {
            $torrent = [];

            $tabId = $torrentTabLink->attr('href');
            $name = $torrentTabLink->text();

            $tab = $mediaNode->filter($tabId)->first();

            $torrent['url'] = $tab->filter('.btn__magnet')->first()->attr('href');

            $series = $tab->filter('.tracker_info_left > h3')->first()->text();
            $torrent['name'] = "{$name} ({$series})";
            $torrent['season'] = $this->_parseSeason($name, $series);

            $torrent['quality'] = $this->_parseQualityFromTab($tab);
            $torrent['size'] = $this->_parseSizeFromTab($tab);
            $torrent['downloads'] = (int)$tab->filter('.circle_grey_text_top > h3')->first()->text();

            $torrent['voice_acting'] = $voiceActing;
            $torrent['content_type'] = $this->_getContentTypeByTitle($name);

            $torrents->add($torrent);
        });

        return $torrents;
    }

    private function _parseSeason(string $name, string $series): ?array {
        if (!preg_match('/сезон/ui', $name)) {
            return null;
        }

        $nameParts = explode(' ', $name);
        $seasonNumber = null;
        foreach ($nameParts as $namePart) {
            if (is_numeric($namePart)) {
                $seasonNumber = (int)$namePart;
                break;
            }
        }

        preg_match('/\d+-\d+/', $series, $matches);
        $seriesCount = (int)array_map('trim', explode('-', $matches[0]))[1];

        return [$seasonNumber, $seriesCount];
    }

    private function _parseOriginalTitle(Crawler $mediaNode): ?string {
        $infoNodes = $mediaNode->filter('.media__post__info .releases-date');
        foreach ($infoNodes as $infoNode) {
            if (mb_strpos($infoNode->textContent, 'Оригинальное название') !== false) {
                return trim(str_replace('Оригинальное название:', '', $infoNode->textContent));
            }
        }

        return null;
    }

    private function _parseSizeFromTab(Crawler $torrentTab): ?string {
        $infoNodes = $torrentTab->filter('.tracker_info_left .releases-track');
        foreach ($infoNodes as $infoNode) {
            if (mb_strpos($infoNode->textContent, 'Размер') !== false) {
                return trim(str_replace('Размер:', '', $infoNode->childNodes[1]->textContent));
            }
        }

        return null;
    }

    private function _parseQualityFromTab(Crawler $torrentTab): ?string {
        foreach ($torrentTab->filter('.tracker_info_left')->first()->getNode(0)->childNodes as $childNode) {
            if ($childNode->nodeName === '#text' && mb_strpos($childNode->wholeText, 'Качество') !== false) {
                return trim(str_replace('Качество', '', $childNode->wholeText));
            }
        }

        return null;
    }

    private function _getContentTypeByTitle(string $title): string {
        $lcTitle = mb_strtolower($title);

        if (mb_strpos($lcTitle, 'сезон') !== false) {
            return Torrent::TYPE_ANIME;
        }

        if (mb_strpos($lcTitle, 'серии') !== false) {
            return Torrent::TYPE_ANIME;
        }

        if (mb_strpos($lcTitle, 'фильм') !== false) {
            return Torrent::TYPE_MOVIE;
        }

        if (mb_strpos($lcTitle, 'ова') !== false) {
            return Torrent::TYPE_ANIME;
        }

        if (mb_strpos($lcTitle, 'ova') !== false) {
            return Torrent::TYPE_ANIME;
        }

        throw new Exceptions\MediaParsingException("[Animedia] Unable to detect content type by title: {$title}");
    }

    private function _parseVoiceActing(Crawler $itemNode): ?string {
        $info = $itemNode->filter('.widget__post-info__voicers__links__item')->first();
        $voiceActing = $info->text();
        return implode(' & ', array_map('trim', explode('&', $voiceActing)));
    }
}