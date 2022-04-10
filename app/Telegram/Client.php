<?php

namespace App\Telegram;

use App\Models\Torrent;
use App\Models\TorrentDownload;
use App\Services\Http\Requester;
use Illuminate\Support\Collection;

class Client
{
    private $_httpRequester;

    public function __construct(Requester $requester) {
        $this->_httpRequester = $requester;
    }

    public function notifyAboutFinishedDownload(Torrent $torrent): void {
        $this->_sendMessageToChannel('Загрузка завершена', $this->getDownloadName($torrent));
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

    private function getDownloadName(Torrent $torrent): string {
        $mediaTitle = $torrent->media->title;
        if ($torrent->content_type === Torrent::TYPE_MOVIE) {
            return $mediaTitle;
        }

        return "{$mediaTitle} / {$torrent->name}";
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
