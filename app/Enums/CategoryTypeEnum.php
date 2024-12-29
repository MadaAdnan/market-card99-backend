<?php

namespace App\Enums;

enum CategoryTypeEnum: string
{
    case ONLINE = 'online';
    case SIM90 = 'sim90';
    case DEFAULT = 'default';

    public function status()
    {
        return match ($this) {
            self::ONLINE => 'أون لاين',
            self::SIM90 => 'سيم 90',
            self::DEFAULT => 'قسم عادي'
        };
    }
}
