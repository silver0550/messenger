<?php

namespace App\Http\Requests\Message;

use App\Http\Requests\BaseRequest;

class IndexMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'orderById' => ['nullable', 'boolean'],
            'only' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'orderById' => __('message.orderById'),
            'only' => __('message.only'),
        ];
    }
}
