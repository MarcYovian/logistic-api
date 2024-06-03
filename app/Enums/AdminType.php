<?php

namespace App\Enums;

enum AdminType: string
{
    case LOGISTIK = "logistik";
    case SSC = "ssc";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
