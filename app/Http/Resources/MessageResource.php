<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    //TODO:
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sender_id' => $this->sender_id,
            'recipient_id' => $this->recipient_id,
            'message' => $this->message,
        ];
    }
}
