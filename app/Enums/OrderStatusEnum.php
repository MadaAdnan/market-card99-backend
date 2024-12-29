<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case WAITE = 'waite';
    case CANCEL = 'cancel';
    case COMPLETE = 'complete';

    public function status()
    {
        return match ($this) {
            self::WAITE => 'بإنتظار الكود',
            self::CANCEL => 'طلب ملغى',
            self::COMPLETE => 'تم إصدار الكود'
        };
    }

    public function color()
    {
        return match ($this) {
            self::WAITE => 'info',
            self::CANCEL => 'warning',
            self::COMPLETE => 'success'
        };
    }
}
