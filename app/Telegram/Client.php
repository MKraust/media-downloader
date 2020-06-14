<?php

namespace App\Telegram;

use GuzzleHttp;

class Client
{
    public function notifyAboutFinishedDownload(string $torrentName): void {
        $httpClient = new GuzzleHttp\Client;
        $url = $this->getMessageSendingUrl();
        $httpClient->get($url, [
            'query' => [
                'chat_id'    => $this->getChatId(),
                'parse_mode' => 'markdown',
                'text'       => "*Загрузка завершена*\n\n`{$torrentName}`",
            ],
        ]);
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