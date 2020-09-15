<?php

namespace App\Http\Requests\Webhook;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $hash
 * @property-read string $path
 */
class FinishDownload extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hash' => 'required|string',
            'path' => 'required|string',
        ];
    }
}
