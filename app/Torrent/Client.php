<?php

namespace App\Torrent;

use App\Jobs\RefreshTorrentDownloads;
use App\Models\Torrent;
use App\Models\TorrentDownload;
use App\Services\Http\Requester;
use App\Services\Files;
use App\Telegram;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Client
{
    private const FILE_PRIORITY_DO_NOT_DOWNLOAD = 0;
    private const FILE_PRIORITY_NORMAL = 1;
    private const FILE_PRIORITY_HIGH = 6;
    private const FILE_PRIORITY_MAX = 7;

    private const BASE_URL = 'http://torrent.mkraust.ru';

    private const GET_TORRENTS      = self::BASE_URL . '/query/torrents';
    private const GET_TORRENT_FILES = self::BASE_URL . '/api/v2/torrents/files';
    private const SET_FILE_PRIORITY = self::BASE_URL . '/api/v2/torrents/filePrio';

    private const START_DOWNLOAD_URL = self::BASE_URL . '/command/download';
    private const DELETE_DOWNLOAD    = self::BASE_URL . '/command/deletePerm';
    private const PAUSE_DOWNLOAD     = self::BASE_URL . '/command/pause';
    private const RESUME_DOWNLOAD    = self::BASE_URL . '/command/resume';

    private const BASE_SAVE_PATH = '/home/kraust/Public/Video/';

    private Telegram\Client $_telegram;

    private Requester $_httpRequester;

    private Files\Renamer $_filesRenamer;

    public function __construct(Telegram\Client $telegram, Requester $requester, Files\Renamer $filesRenamer) {
        $this->_telegram = $telegram;
        $this->_httpRequester = $requester;
        $this->_filesRenamer = $filesRenamer;
    }

    public function refreshDownloads() {
        $downloadsData = $this->_getDownloadsData();

        $existingDownloads = TorrentDownload::active()->get();
        $downloads = $downloadsData->map(static function (array $downloadData) {
            return Download::createFromRemoteData($downloadData)->convertIntoModel();
        });

        $removedDownloads = $existingDownloads->filter(fn(TorrentDownload $download) => !$downloads->contains('hash', $download->hash));
        $newDownloads = $downloads->filter(fn(TorrentDownload $download) => !$existingDownloads->contains('hash', $download->hash));

        $newDownloads->each(function (TorrentDownload $download) {
            TorrentDownload::updateOrCreate(['hash' => $download->hash], $download->toArray());

            if ($download->torrent->content_type !== Torrent::TYPE_ANIME) {
                return;
            }

            $files = $this->_getDownloadFiles($download->hash);
            if (count($files) === 0) {
                return;
            }

            $directoryName = explode('/', $files[0]['name'])[0];
            $contentPath = self::BASE_SAVE_PATH . "/Anime/{$directoryName}";
            $existingFiles = collect($this->_filesRenamer->getRenamedFiles($contentPath));

            $notNeededFileIds = [];
            foreach ($files as $index => $file) {
                $fileName = explode('/', $file['name'])[0];
                if ($existingFiles->contains(fn(Files\RenamedFile $renamedFile) => $renamedFile->from() === $fileName)) {
                    $notNeededFileIds[] = $index;
                }
            }

            if (count($notNeededFileIds) > 0) {
                $excludedFileNames = array_map(fn($id) => $files[$id]['name'],$notNeededFileIds);
                Log::info("Excluding files from download {$download->hash}: " . implode("\n\t", $excludedFileNames));
                $this->_setFilesPriority($download->hash, $notNeededFileIds, self::FILE_PRIORITY_DO_NOT_DOWNLOAD);
            } else {
                Log::info("No files to exclude from download {$download->hash}");
            }
        });

        $removedDownloads->each(function (TorrentDownload $download) {
            if (!$download->is_deleted) {
                $this->_telegram->notifyAboutFinishedDownload($download);
            }

            $download->delete();
        });
    }

    /**
     * @return Collection|Download[]
     */
    public function getDownloads(): Collection {
        $downloads = TorrentDownload::active()->get();
        $hashes = $downloads->map->hash->toArray();

        $downloadsData = $this->_getDownloadsData($hashes);
        return $downloadsData
            ->filter(static function (array $downloadData) {
                return preg_match('/^id:\d+$/', $downloadData['name']);
            })
            ->map(static function (array $downloadData) {
                return Download::createFromRemoteData($downloadData);
            });
    }

    /**
     * @param int[] $fileIds
     */
    private function _setFilesPriority(string $hash, array $fileIds, int $priority): void {
        $this->_httpRequester->post(self::SET_FILE_PRIORITY, [
            'hash' => $hash,
            'id' => implode('|', $fileIds),
            'priority' => $priority,
        ]);
    }

    /**
     * @param string[] $hashes
     * @return Collection
     */
    private function _getDownloadsData(array $hashes = []): Collection {
        $params = [
            'hashes' => implode('|', $hashes),
        ];

        $response = $this->_httpRequester->get(self::GET_TORRENTS, $params);
        return collect(json_decode($response, true));
    }

    private function _getDownloadFiles(string $hash): array {
        $response = $this->_httpRequester->get(self::GET_TORRENT_FILES, [
            'hash' => $hash,
        ]);

        return json_decode($response, true);
    }

    public function startDownload(Torrent $torrent, string $fileUrl): void {
        $this->_httpRequester->postMultipart(self::START_DOWNLOAD_URL, [
            'urls'     => $fileUrl,
            'savepath' => self::BASE_SAVE_PATH . $this->_getDirectoryByContentType($torrent->content_type),
            'rename'   => "id:{$torrent->id}",
        ]);

        RefreshTorrentDownloads::dispatch()->delay(now()->addSeconds(5));
    }

    public function deleteDownload(string $hash): void {
        $download = TorrentDownload::find($hash);
        $download->is_deleted = true;
        $download->save();

        $this->_httpRequester->post(self::DELETE_DOWNLOAD, [
            'hashes' => $hash,
        ]);
    }

    public function pauseDownload(string $hash): void {
        $this->_httpRequester->post(self::PAUSE_DOWNLOAD, [
            'hash' => $hash,
        ]);
    }

    public function resumeDownload(string $hash): void {
        $this->_httpRequester->post(self::RESUME_DOWNLOAD, [
            'hash' => $hash,
        ]);
    }

    private function _getDirectoryByContentType(string $contentType): string {
        switch ($contentType) {
            case Torrent::TYPE_ANIME:
                return 'Anime';
            case Torrent::TYPE_SERIES:
                return 'Serials';
            case Torrent::TYPE_MOVIE:
                return 'Movies';
        }
    }
}
