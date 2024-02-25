<?php

namespace App\Services;

use App\Repositories\MessageRepository;

class MessageService
{

    public function __construct(private readonly MessageRepository $messageRepositroy)
    {
    }
}
