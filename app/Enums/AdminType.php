<?php

namespace App\Enums;

enum AdminType: string
{
    case LOGISTIK = "logistik";
    case SSC = "ssc";
    case SUPERUSER = "superuser";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function allowedValues(): array
    {
        return [
            self::LOGISTIK->value,
            self::SSC->value,
        ];
    }
}
