<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function getReadableText(): string
    {
        return match($this){
            self::ACTIVE => __('status.active'),
            self::INACTIVE => __('status.inactive'),
        };
    }
}
