<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected string $model;

    public function __construct()
    {
        $this->model = $this->determineModelClass();
    }

    abstract protected function determineModelClass(): string;

    public function getAll(): Collection
    {
        return $this->model::all();
    }

    public function getById($id): ?Model
    {
        return $this->model::find($id);
    }

    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data): void
    {
        $this->getById($id)->update($data);
    }

    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }

}
