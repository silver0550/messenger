<?php

namespace App\Observers;

use App\Models\Message;

class MessageObserver
{
    public function creating(Message $message): void
    {
        $message->sender_id = $message->sender_id ?? userId();
    }
}
