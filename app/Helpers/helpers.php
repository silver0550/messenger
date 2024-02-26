<?php

use App\Models\User;

if (!function_exists('user')) {
    function user(): ?User
    {
        return Auth::user();
    }
}

if (!function_exists('userId')) {
    function userId(): ?int
    {
        return Auth::user()?->id;
    }
}
