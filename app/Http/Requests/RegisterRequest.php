<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255', 'unique:users', 'regex:/^[^\d]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('auth.name'),
            'email' => __('auth.email'),
            'password' => __('auth.password'),
        ];
    }
}
