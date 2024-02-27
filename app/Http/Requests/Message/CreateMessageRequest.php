<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class CreateMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient' => ['nullable', 'string', 'max:255'],
            'message' => ['required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'recipient' => __('message.recipient'),
            'message' => __('message.message'),
        ];
    }
}
