<?php

namespace App\Http\Requests;

class MessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'recipient_id' => ['required', 'integer'],
            'message' => ['required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'recipient_id' => __('message.recipient'),
            'message' => __('message.message'),
        ];
    }
}
