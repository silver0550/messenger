<?php

namespace App\Http\Requests\Message;

use App\Http\Requests\BaseRequest;

class CreateMessageRequest extends BaseRequest
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
