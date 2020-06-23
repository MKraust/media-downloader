<?php


namespace App\Torrent;


use App\Models\Media;
use App\Models\Torrent;
use App\Models\TorrentDownload;

class Download implements \JsonSerializable {

    private $_hash;

    private $_originalName;

    private $_downloadSpeedInBytesPerSecond;

    private $_estimateInSeconds;

    private $_sizeInBytes;

    private $_stateOriginal;

    private $_progress;

    public function __construct(string $hash, string $originalName, int $downloadSpeedInBytesPerSecond, int $estimateInSeconds, int $sizeInBytes, string $stateOriginal, float $progress) {
        $this->_hash = $hash;
        $this->_originalName = $originalName;
        $this->_downloadSpeedInBytesPerSecond = $downloadSpeedInBytesPerSecond;
        $this->_estimateInSeconds = $estimateInSeconds;
        $this->_sizeInBytes = $sizeInBytes;
        $this->_stateOriginal = $stateOriginal;
        $this->_progress = $progress;
    }

    public static function createFromRemoteData(array $data): self {
        return new self(
            $data['hash'],
            $data['name'],
            $data['dlspeed'],
            $data['eta'],
            $data['size'],
            $data['state'],
            $data['progress']
        );
    }

    public function convertIntoModel(): TorrentDownload {
        return new TorrentDownload([
            'hash'       => $this->_hash,
            'torrent_id' => $this->_getTorrentId(),
        ]);
    }

    public function hash(): string {
        return $this->_hash;
    }

    public function progress(): float {
        return $this->_progress;
    }

    public function jsonSerialize(): array {
        return [
            'hash'                               => $this->_hash,
            'name'                               => $this->_originalName,
            'download_speed_in_bytes_per_second' => $this->_downloadSpeedInBytesPerSecond,
            'estimate_in_seconds'                => $this->_estimateInSeconds,
            'size_in_bytes'                      => $this->_sizeInBytes,
            'state_original'                     => $this->_stateOriginal,
            'progress'                           => (string)$this->_progress,
            'media'                              => $this->_getMedia(),
            'torrent'                            => $this->_getTorrent(),
        ];
    }

    private function _getTorrentId(): int {
        return str_replace('id:', '', $this->_originalName);
    }

    private function _getMedia(): Media {
        return $this->_getTorrent()->media;
    }

    private function _getTorrent(): Torrent {
        $torrentId = $this->_getTorrentId();
        return Torrent::find($torrentId);
    }
}