<?php

namespace App\Enums;

enum BillStatusEnum: string
{
    case PENDING = 'pending';
    case CANCEL = 'cancel';
    case COMPLETE = 'complete';
    case REQUEST_CANCEL = 'request_cancel';
    case SUCCESS = 'success';

    public function status()
    {
        return match ($this) {
            self::PENDING => 'بالإنتظار',
            self::CANCEL => 'طلب ملغى',
            self::COMPLETE => 'طلب مكتمل',
            self::REQUEST_CANCEL => 'طلب إلغاء',
            self::SUCCESS => 'تم الشحن'
        };
    }

    public function color()
    {
        return match ($this) {
            self::PENDING => 'secondary',
            self::CANCEL => 'danger',
            self::COMPLETE, self::SUCCESS => 'success',
            self::REQUEST_CANCEL => 'info'
        };
    }
}
