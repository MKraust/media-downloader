<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Telegram;

class NotificationController extends Controller
{
    /** @var Telegram\Client */
    private $_telegram;

    public function __construct(Telegram\Client $telegram)
    {
        $this->_telegram = $telegram;
    }

    public function notifyAboutFinishedDownload()
    {
        $torrentName = request('name');
        $this->_telegram->notifyAboutFinishedDownload($torrentName);
    }
}
