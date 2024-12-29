<?php

namespace App;

use App\Models\Bill;

class CheckOrder
{
    public static function checkIdPlayer($id): bool
    {
        $bill = Bill::whereNotNull('customer_id')->where('customer_id', $id)->latest()->first();
        if (!$bill) {
            return true;
        }
        return now()->subSeconds(20)->greaterThan($bill->created_at);
    }
}
