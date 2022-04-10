<?php

namespace App\Http\Requests\Torrent;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
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
            'name' => 'required|string',
            'path' => 'required|string',
            'hash' => 'required|string',
        ];
    }
}
