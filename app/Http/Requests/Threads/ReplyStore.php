<?php

namespace App\Http\Requests\Threads;

use Illuminate\Foundation\Http\FormRequest;

class ReplyStore extends FormRequest
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
            'body' => 'required'
        ];
    }
}
