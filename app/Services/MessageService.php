<?php

namespace App\Services;

use App\Models\Message;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{

    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly UserRepository $userRepository
    )
    {
    }

    public function createMessage(array $data): Message
    {
        $data['recipient_id'] = isset($data['recipient'])
            ? $this->userRepository->getIdByName($data['recipient'])
            : null;

        return $this->messageRepository->create($data);
    }

    public function getFilteredMessages(bool $orderById = false, ?string $only = null): Collection
    {
        return $this->messageRepository->getFilteredMessages($orderById, $only);
    }
}
