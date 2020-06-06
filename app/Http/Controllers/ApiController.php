<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function trackers() {
        return [
            ['id' => 'anidub', 'title' => 'AniDub'],
            ['id' => 'animedia', 'title' => 'AniMedia'],
            ['id' => 'fast_torrent', 'title' => 'Fast Torrent'],
        ];
    }

    public function search() {
        $trackerName = request('tracker');
        $searchQuery = request('query');

        $result = [];
        for ($i = 1; $i <= 8; $i++) {
            $url = "http://example.com/{$i}";
            $result[] = [
                'id' => encrypt($url),
                'title' => $trackerName . ' | ' . $searchQuery,
                'poster' => 'https://static.animedia.tv/uploads/1202111.jpg'
            ];
        }

        return $result;
    }

    public function media() {
        $id = request('id');
        $url = decrypt($id);

        return [
            'id' => $id,
            'title' => 'Семь смертных грехов',
            'original_title' => 'Nanatsu no Taizai',
            'poster' => 'https://static.animedia.tv/uploads/1202111.jpg',
            'url' => $url,
            'series' => null,
            'year' => 2014,
            'type' => 'anime',
            'torrents' => [
                [
                    'id' => encrypt('https://static.animedia.tv/uploads/1202111.jpg'),
                    'url' => 'https://static.animedia.tv/uploads/1202111.jpg',
                    'name' => '1 сезон',
                    'size' => '1.4 ГБ',
                    'quality' => '720p',
                    'voice_acting' => 'KASHI & NAZEL & OZIRIST',
                    'season' => '1 сезон (1-5)',
                    'content_type' => 'anime',
                ],
                [
                    'id' => encrypt('https://static.animedia.tv/uploads/1202111.jpg'),
                    'url' => 'https://static.animedia.tv/uploads/1202111.jpg',
                    'name' => '2 сезон',
                    'size' => '1.4 ГБ',
                    'quality' => '720p',
                    'voice_acting' => 'KASHI & NAZEL & OZIRIST',
                    'season' => '2 сезон (1-5)',
                    'content_type' => 'anime',
                ],
                [
                    'id' => encrypt('https://static.animedia.tv/uploads/1202111.jpg'),
                    'url' => 'https://static.animedia.tv/uploads/1202111.jpg',
                    'name' => '3 сезон',
                    'size' => '1.4 ГБ',
                    'quality' => '720p',
                    'voice_acting' => 'KASHI & NAZEL & OZIRIST',
                    'season' => '3 сезон (1-5)',
                    'content_type' => 'anime',
                ],
            ],
        ];
    }
}
