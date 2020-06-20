<?php

namespace App\Tracker\Animedia;

use Symfony\Component\DomCrawler\Crawler;

class MediaPageParser
{
    public function parse(string $html): array {
        $crawler = new Crawler($html);

        $result = [];


    }
}