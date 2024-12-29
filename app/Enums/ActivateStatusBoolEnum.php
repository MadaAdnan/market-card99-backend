<?php

namespace App\Enums;

enum ActivateStatusBoolEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;


    public function status()
    {
        return match ($this) {
            self::ACTIVE => 'مفعل',
            self::INACTIVE => 'غير مفعل',

        };
    }
    public function coupon()
    {
        return match ($this) {
            self::ACTIVE => 'غير مستخدم',
            self::INACTIVE => 'مستخدم',

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
