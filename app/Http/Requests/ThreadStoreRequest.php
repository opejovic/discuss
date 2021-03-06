<?php

namespace App\Http\Requests;

use App\Rules\SpamRule;
use Illuminate\Foundation\Http\FormRequest;

class ThreadStoreRequest extends FormRequest
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
            'title'      => ['required', 'min:2', new SpamRule],
            'body'       => ['required', 'min:2', new SpamRule],
            'channel_id' => ['required', 'exists:channels,id'],
        ];
    }
}
