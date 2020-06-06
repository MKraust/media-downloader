<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function trackers() {
        return ['AniDub', 'AniMedia', 'Fast Torrent'];
    }

    public function search() {
        $trackerName = request('tracker');
        $searchQuery = request('query');

        $result = [];
        for ($i = 1; $i <= 3; $i++) {
            $result[] = [
                'id' => $i,
                'title' => $trackerName . ' | ' . $searchQuery,
                'poster' => 'https://static.animedia.tv/uploads/1202111.jpg'
            ];
        }

        return $result;
    }
}
