<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'sender' => User::find($this->sender_id)->name,
            'recipient' => User::find($this->recipient_id)?->name ?? 'all',
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
