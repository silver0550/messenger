<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
