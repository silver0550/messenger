<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class IndexMessageRequest extends FormRequest
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
