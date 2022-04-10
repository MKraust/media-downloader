<?php

namespace App\Tracker\Anidub;

class TitleParser {

    public function parseTitle(string $fullTitle): string {
        $titleParts = explode('/ ', $fullTitle); // @todo fix this as in titles may be slashes
        $trimmedTitleParts = array_map('trim', $titleParts);

        return $trimmedTitleParts[0];
    }

    public function parseOriginalTitle(string $fullTitle): ?string {
        $titleParts = explode('/ ', $fullTitle); // @todo fix this as in titles may be slashes

        // return null if no slash was found
        // example: Инуяша: Замок в зазеркалье Inuyasha: Kagami no Naka no Mugenjo [Movie-2]
        // https://tr.anidub.com/anime_movie/3270-inuyasha-zamok-v-zazerkale-inuyasha-kagami-no-naka-no-mugenjo-movie-22002dvd-5.html
        if (count($titleParts) < 2) {
            return null;
        }

        $trimmedTitleParts = array_map('trim', $titleParts);

        return trim(preg_replace('/\[.+]/u', '', $trimmedTitleParts[1]));
    }

    public function parseSeriesCount(string $fullTitle): ?string {
        preg_match('/\[.+]/u', $fullTitle, $seriesCount);
        $seriesCount = $seriesCount[0] ?? null;

        return $seriesCount !== null ? str_replace(['[', ']'], '', $seriesCount) : null;
    }
}
