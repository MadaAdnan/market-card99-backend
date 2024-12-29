<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\InterFaces\ServerInterface;
use App\Jobs\OrderClearJob;
use App\Models\Order;
use Illuminate\Console\Command;

class CancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders=Order::where('status',OrderStatusEnum::WAITE->value)/*->limit(10)->orderBy('id')*/->where('created_at','>' , now()->subDays(15))->where('created_at','<',now()->subMinutes(5))->with('server')->get();
        foreach ($orders as $order){

            dispatch(new OrderClearJob($order));
        }
        return 0;
    }
}
