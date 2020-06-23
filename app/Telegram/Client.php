<?php

namespace App\Telegram;

use App\Models\TorrentDownload;
use GuzzleHttp;

class Client
{
    public function notifyAboutFinishedDownload(TorrentDownload $download): void {
        $httpClient = new GuzzleHttp\Client;
        $url = $this->getMessageSendingUrl();
        $httpClient->get($url, [
            'query' => [
                'chat_id'    => $this->getChatId(),
                'parse_mode' => 'markdown',
                'text'       => "*Загрузка завершена*\n{$this->getDownloadName($download)}",
            ],
        ]);
    }

    private function getDownloadName(TorrentDownload $download): string {
        $mediaTitle = $download->torrent->media->title;
        if ($download->torrent->content_type === 'movie') {
            return $mediaTitle;
        }

        return "{$mediaTitle} / {$download->torrent->name}";
    }

    private function getBotKey(): string {
        return env('TELEGRAM_BOT_KEY');
    }

    private function getChatId(): string {
        return env('TELEGRAM_CHANNEL_CHAT_ID');
    }

    private function getMessageSendingUrl(): string {
        return "https://api.telegram.org/bot{$this->getBotKey()}/sendMessage";
    }
}