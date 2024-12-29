<?php

namespace App\Http\Livewire\Admin\Statistics;

use App\Enums\OrderStatusEnum;
use App\Helpers\Yutu;
use App\Models\Balance;
use App\Models\Order;
use App\Models\Server;
use Carbon\Carbon;
use Livewire\Component;

class TotalBalanceComponent extends Component
{
   public $server;
    public function mount(){
        $this->server=Server::where('code',Yutu::class)->first();
    }
    public function render()
    {
        return view('livewire.admin.statistics.total-balance-component',[
            'total'=>\DB::table('balances')->selectRaw('SUM(credit)-SUM(debit) as total')->first(),
            'orders'=>Order::whereServerId($this->server->id)->whereStatus(OrderStatusEnum::COMPLETE->value)
                ->where('orders.created_at','>',Carbon::parse(date('Y-m-d h:i:s',strtotime('2023-08-3 00:00:00'))))
                ->selectRaw('Sum(price) as total,count(id) as count')->first(),
        ]);
    }
}
