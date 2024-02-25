<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class AuthService
{

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }
}
