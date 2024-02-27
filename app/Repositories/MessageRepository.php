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
            ->with([
                'sender' => function ($query) {
                    return $query->select('id', 'name');
                }
            ])
            ->when($only, fn($query) => $query->whereRelation('sender', 'name', 'like', '%'.$only.'%'))
            ->when($orderById, fn($query) => $query->orderBy('id'))
            ->get();
    }

    public function getFirst(): ?Message
    {
        return $this->model::first();
    }
}
