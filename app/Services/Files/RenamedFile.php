<?php

namespace App\Services\Files;

class RenamedFile implements \JsonSerializable {

    private string $_from;

    private string $_to;

    public function __construct(string $from, string $to) {
        $this->_from = $from;
        $this->_to = $to;
    }

    public function from(): string {
        return $this->_from;
    }

    public function to(): string {
        return $this->_to;
    }

    public function jsonSerialize(): array {
        return [
            'from' => $this->from(),
            'to' => $this->to(),
        ];
    }
}