<?php

namespace App\Console\Commands;

use App\Enums\BillStatusEnum;
use App\FromApi\As7ab;
use App\FromApi\CachBack;
use App\FromApi\Drd3;
use App\FromApi\EkoCard;
use App\FromApi\Juneed;
use App\FromApi\LifeCash;
use App\FromApi\Mazaya;
use App\FromApi\PayByApi;
use App\FromApi\SaudCard;
use App\FromApi\SpeedCard;
use App\Models\Bill;
use App\Models\Setting;
use Illuminate\Console\Command;

class CompleteOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:status';

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
        $orders = Bill::where('status', \App\Enums\BillStatusEnum::PENDING->value)->whereNotNull('api_id')->get();
        $setting = Setting::first();

        foreach ($orders as $order) {

            switch ($order->api) {
                case 'life-cash':
                    $service = new LifeCash($setting);
                    break;
                case 'speed-card':
                    $service = new SpeedCard($setting);
                    break;
                case 'eko':
                    $service = new EkoCard($setting);
                    break;
                case 'drd3':
                    $service = new Drd3($setting);
                    break;
                case 'saud':
                    $service = new SaudCard($setting);
                    break;
                case 'as7ab':
                    $service = new As7ab($setting);
                    break;
                case 'mazaya':
                    $service = new Mazaya($setting);
                    break;
                case 'cache-back':
                    $service = new CachBack($setting);
                    break;
                    case 'juneed':
                    $service = new Juneed($setting);
                    break;
            }
            /**
             * @var $service PayByApi
             * @var $order Bill
             */
            $service->checkStatus($order);


        }

        return 0;
    }
}
