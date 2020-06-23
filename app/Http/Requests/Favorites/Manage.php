<?php

namespace App\Http\Requests\Favorites;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $id
 */
class Manage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|string',
        ];
    }
}
