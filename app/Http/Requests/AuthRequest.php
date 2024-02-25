<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:8'],
            'device_name' => ['required', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => __('auth.email'),
            'password' => __('auth.password'),
            'device_name' => __('auth.device_name'),
        ];
    }
}
