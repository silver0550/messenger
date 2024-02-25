<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{

    protected function determineModelClass(): string
    {
        return User::class;
    }
}
