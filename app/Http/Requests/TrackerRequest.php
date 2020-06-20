<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Tracker;

class TrackerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Tracker\Keeper $trackerKeeper */
        $trackerKeeper = app()->make(Tracker\Keeper::class);
        $trackerIds = $trackerKeeper->getTrackerIds();

        return [
            'tracker' => [
                'required',
                Rule::in($trackerIds),
            ],
        ];
    }
}
