<?php

namespace App\Enums;

enum CurrencyEnum: string
{
    case USD = 'usd';
    case TR = 'tr';

    public function status()
    {
        return match ($this) {
            self::USD => 'دولار',
            self::TR => 'تركي',

        };
    }
}
