<?php


namespace App\Torrent;


use App\Models\TorrentDownload;

class Download implements \JsonSerializable {

    private $_hash;

    private $_name;

    private $_downloadSpeedInBytesPerSecond;

    private $_estimateInSeconds;

    private $_sizeInBytes;

    private $_stateOriginal;

    private $_progress;

    public function __construct(string $hash, string $name, int $downloadSpeedInBytesPerSecond, int $estimateInSeconds, int $sizeInBytes, string $stateOriginal, float $progress) {
        $this->_hash = $hash;
        $this->_name = $name;
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
            'hash' => $this->_hash,
            'name' => $this->_name,
        ]);
    }

    public function hash(): string {
        return $this->_hash;
    }

    public function name(): string {
        return $this->_name;
    }

    public function downloadSpeedInBytesPerSecond(): int {
        return $this->_downloadSpeedInBytesPerSecond;
    }

    public function estimateInSeconds(): int {
        return $this->_estimateInSeconds;
    }

    public function sizeInBytes(): int {
        return $this->_sizeInBytes;
    }

    public function stateOriginal(): string {
        return $this->_stateOriginal;
    }

    public function progress(): float {
        return $this->_progress;
    }

    public function jsonSerialize(): array {
        return [
            'hash'                               => $this->_hash,
            'name'                               => $this->_name,
            'download_speed_in_bytes_per_second' => $this->_downloadSpeedInBytesPerSecond,
            'estimate_in_seconds'                => $this->_estimateInSeconds,
            'size_in_bytes'                      => $this->_sizeInBytes,
            'state_original'                     => $this->_stateOriginal,
            'progress'                           => (string)$this->_progress,
        ];
    }
}