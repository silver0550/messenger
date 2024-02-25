<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sender_id' => ['required', 'integer'],
            'recipient_id' => ['required', 'integer'],
            'message' => ['required'],
        ];
    }
}
