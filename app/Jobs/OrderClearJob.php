<?php

namespace App\Jobs;

use App\Enums\OrderStatusEnum;
use App\Helpers\Viotp;
use App\InterFaces\ServerInterface;
use App\Models\Balance;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderClearJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
public Order $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if(!class_exists($this->order->server->code)){
            \DB::beginTransaction();
            try {
                if ($this->order->status->value == OrderStatusEnum::WAITE->value) {
                    $price=$this->order->price;
                    if(Balance::where('info','إعادة قيمة رقم ' . $this->order->program?->name.' #'.$this->order->id)->count()==0){
                        Balance::create([
                            'user_id' => $this->order->user_id,
                            'credit' => $price,
                            'debit' => 0,
                            'info' => 'إعادة قيمة رقم ' . $this->order->program?->name.' #'.$this->order?->id,
                            'total' => $this->order->user->balance + $price,

                        ]);
                    }


                }
                $this->order->update([
                    'status' => OrderStatusEnum::CANCEL->value,
                    'code' => 'ملغي'
                ]);

                \DB::commit();
            } catch (\Exception | \Error $e) {
                \DB::rollBack();
                throw (new \Exception('حدث خطأ في معالجة الطلب'));
            }
        }else{
            /**
             * @var $lib Viotp
             */
            $lib=new $this->order->server->code();
            $lib->getPhoneCode($this->order);
        }

    }
}
