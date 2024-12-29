<?php

namespace App\Enums;

enum ProductTypeEnum: string
{
    case NEED_ACCOUNT = 'account';
    case NEED_ID = 'id';
    case PHONE = 'phone';
    case URL = 'url';
    case DEFAULT = 'default';
    case ITEMS = 'items';

    public function status()
    {
        return match ($this) {
            self::NEED_ACCOUNT => 'يحتاج اسم مستخدم وكلمة مرور',
            self::NEED_ID => 'يحتاج رقم هاتف أو ID',
            self::DEFAULT => ' عادي',
            self::ITEMS => 'جاهز',
            self::PHONE => 'رقم هاتف',
            self::URL => 'رابط'
        };
    }
}
