<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{

    protected function determineModelClass(): string
    {
        return User::class;
    }

    public function getIdByName(string $name): ?int
    {
        return $this->model::query()
            ->whereName($name)
            ->first()
            ?->id;
    }
}
