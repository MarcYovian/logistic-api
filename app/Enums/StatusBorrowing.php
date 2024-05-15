<?php

namespace App\Enums;

enum StatusBorrowing: string
{
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
