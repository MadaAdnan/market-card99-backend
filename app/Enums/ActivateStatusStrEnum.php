<?php

namespace App\Enums;

enum ActivateStatusStrEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';


    public function status()
    {
        return match ($this) {
            self::ACTIVE => 'مفعل',
            self::INACTIVE => 'غير مفعل',

        };
    }


    public function color()
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',

        };
    }
}
