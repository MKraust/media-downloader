<?php

namespace App\Telegram;

use App\Models\Torrent;
use App\Models\TorrentDownload;
use App\Services\HttpRequester\Requester;
use Illuminate\Support\Collection;

class Client
{
    private $_httpRequester;

    public function __construct(Requester $requester) {
        $this->_httpRequester = $requester;
    }

    public function notifyAboutFinishedDownload(TorrentDownload $download): void {
        $this->_sendMessageToChannel('Загрузка завершена', $this->getDownloadName($download));
    }

    public function notifyAboutNewEpisodes(Collection $mediaTitles): void {
        $this->_sendMessageToChannel('Новые серии', $mediaTitles->implode("\n"));
    }

    private function _sendMessageToChannel(string $title, string $message): void {
        $url = $this->getMessageSendingUrl();
        $this->_httpRequester->get($url, [
            'chat_id'    => $this->getChatId(),
            'parse_mode' => 'markdown',
            'text'       => "*{$title}*\n\n{$message}",
        ]);
    }

    private function getDownloadName(TorrentDownload $download): string {
        $mediaTitle = $download->torrent->media->title;
        if ($download->torrent->content_type === Torrent::TYPE_MOVIE) {
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