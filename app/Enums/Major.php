<?php

namespace App\Enums;

enum Major: string
{
    case TEKNIK_TELEKOMUNIKASI = "teknik telekomunikasi";
    case TEKNIK_ELEKTRO = "teknik elektro";
    case TEKNIK_KOMPUTER = "teknik komputer";
    case TEKNIK_INDUSTRI = "teknik industri";
    case SISTEM_INFORMASI = "sistem informasi";
    case TEKNIK_LOGISTIK = "teknik logistik";
    case INFORMATIKA = "informatika";
    case REKAYASA_PERANGKAT_LUNAK = "rekayasa perangkat lunak";
    case TEKNOLOGI_INFORMASI = "teknologi informasi";
    case SAINS_DATA = "sains data";
    case BISNIS_DIGITAL = "bisnis digital";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
