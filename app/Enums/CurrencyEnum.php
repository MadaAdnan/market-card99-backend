<?php

namespace App\Enums;

enum CurrencyEnum: string
{
    case USD = 'usd';
    case TR = 'tr';
    case SYR = 'syr';

    public function status()
    {
        return match ($this) {
            self::USD => 'دولار',
            self::TR => 'تركي',
            self::SYR => 'سوري',

        };
    }
}
