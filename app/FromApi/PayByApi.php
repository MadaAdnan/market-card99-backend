<?php

namespace App\FromApi;

use App\Models\Bill;
use App\Models\Order;

interface PayByApi
{

    public function buyFromApiFree(Bill $bill,$code): Bill;

    public function buyFromApiFixed(Bill $bill,$code): Bill;

    public function checkStatus(Bill $bill): Bill;

    public function cancelBill(Bill $bill,$other_data=null): Bill;

}
