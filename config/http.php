<?php

return [
  'requesters' => [
      'anidub'       => env('REQUESTER_ANIDUB', 'requester.base'),
      'animedia'     => env('REQUESTER_ANIMEDIA', 'requester.base'),
      'fast_torrent' => env('REQUESTER_FAST_TORRENT', 'requester.base'),
  ],
];
