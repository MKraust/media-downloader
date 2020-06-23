<?php

namespace App\Http\Requests\Tracker\Blocked;

use Illuminate\Foundation\Http\FormRequest;

class GetSearchUrl extends FormRequest
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
            'tracker_id'   => 'required|string',
            'search_query' => 'required|string',
            'offset'       => 'integer|min:0',
        ];
    }
}
