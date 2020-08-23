<?php

namespace App\Http\Requests\Website;

use App\Facades\Twitter;
use Illuminate\Foundation\Http\FormRequest;

class TweetStoreRequest extends FormRequest
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
            'status' => 'required|max:140',
        ];
    }

    public function storeTweet()
    {
        return Twitter::publish($this->only('status'));
    }
}
