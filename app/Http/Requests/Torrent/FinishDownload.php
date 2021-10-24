<?php

namespace App\Http\Requests\Torrent;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $path
 * @property-read string $hash
 */
class FinishDownload extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'path' => 'required|string',
            'hash' => 'required|string',
        ];
    }
}
