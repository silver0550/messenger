<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository extends BaseRepository
{

    protected function determineModelClass(): string
    {
        return Message::class;
    }
}
