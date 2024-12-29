<?php

namespace App\InterFaces;

use App\Enums\OrderStatusEnum;
use App\Models\Country;
use App\Models\Order;
use App\Models\Program;
use App\Models\Server;

interface ServerInterface
{

    //public function getServerCode():string;
    public function getPriceApp():Price;
    public function getPhoneNumber(Program $program):Order;
    public function getPhoneCode(Order $order):string;
    public function cancelOrder(Order $order):string;
    public function GetBalance():mixed;


}
