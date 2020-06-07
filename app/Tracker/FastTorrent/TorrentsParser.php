<?php


namespace App\Tracker\FastTorrent;


use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class TorrentsParser
{
    public function parse(string $html): Collection {
        $torrents = collect();

        $crawler = new Crawler($html);
        $columns = $this->getColumns($crawler);
        $torrentNodes = $crawler->filter('.torrent-row')->each(function (Crawler $torrentNode) use ($columns, $torrents) {
            $torrent = [];

            $link = $torrentNode->filter('.torrent-info .download-event')->first();
            $torrent['name'] = $link->text();
            $torrent['url'] = Requester::BASE_URL . $link->attr('href');

            $qualityColumnClass = $columns['quality'];
            if ($qualityColumnClass !== null) {
                $torrent['quality'] = $this->parseQuality($torrentNode, $qualityColumnClass);
            }

            $translationColumnClass = $columns['translation'];
            if ($translationColumnClass !== null) {
                $torrent['voice_acting'] = $this->parseTranslation($torrentNode, $translationColumnClass);
            }

            $sizeColumnClass = $columns['size'];
            if ($sizeColumnClass !== null) {
                $torrent['size'] = $this->parseSimpleColumn($torrentNode, $sizeColumnClass);
            }

            if ($columns['downloaded'] !== null) {
                $torrent['downloads'] = (int)$this->parseSimpleColumn($torrentNode, $columns['downloaded']);
            }

            $seasonInfo = $torrentNode->attr('season');
            if ($seasonInfo !== '') {
                $seasonParts = explode('.', $seasonInfo);
                $season = (int)$seasonParts[0];
                $series = (int)$seasonParts[1];
                $torrent['season'] = [$season, $series];
                $torrent['content_type'] = $season === 0 && $series === 0 ? 'movie' : 'series';
            }

            $torrents->add($torrent);
        });

        return $torrents;
    }

    private function parseQuality(Crawler $torrentNode, string $qualityColumnClass): string {
        $qualityNode = $torrentNode->filter(".{$qualityColumnClass}")->first();
        $quality = $qualityNode->filter('.qa-icon')->first()->text();

        $qualityNode->filter('.qa-hd, .qa-3d')->each(static function (Crawler $label) use (&$quality) {
            $quality .= " {$label->text()}";
        });

        return $quality !== '' ? trim($quality) : 'Low';
    }

    private function parseTranslation(Crawler $torrentNode, string $translationColumnClass): string {
        $translationNode = $torrentNode->filter(".{$translationColumnClass}")->first();
        $translationTextNode = $translationNode->filter('b')->first();
        if ($translationTextNode === null) {
            return $translationNode->text();
        }

        $studios = [];
        $translation = $translationTextNode->text();
        $translationNode->filter('a')->each(static function (Crawler $node) use (&$studios) {
            $studios[] = $node->text();
        });

        return $translation . ' ' . implode(', ', $studios);
    }

    private function parseSimpleColumn(Crawler $torrentNode, string $columnClass): string {
        return $torrentNode->filter(".{$columnClass}")->first()->text();
    }

    private function getColumns(Crawler $crawler): array {
        $columns = [
            'quality' => null,
            'translation' => null,
            'size' => null,
            'downloaded' => null,
            'season' => null,
        ];

        $crawler->filter('.upload-header > div')->each(static function (Crawler $tableHeaderNode) use (&$columns) {
            $type = $tableHeaderNode->attr('rel');
            if (!array_key_exists($type, $columns)) {
                return;
            }

            $classNames = collect(explode(' ', $tableHeaderNode->attr('class')));
            $columns[$type] = $classNames->first(static function (string $class) {
                return preg_match('~^c\d+$~', $class);
            });
        });

        return $columns;
    }
}
