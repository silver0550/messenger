<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository extends BaseRepository
{

    protected function determineModelClass(): string
    {
        return Message::class;
    }

    public function getFilteredMessages(bool $orderById = false, ?string $only = null): Collection
    {
        return $this->model::query()
            ->when($only, fn($query) => $query->whereRelation('sender', 'name', $only))
            ->when($orderById, fn($query) => $query->orderBy('id'))
            ->get();
    }
}
