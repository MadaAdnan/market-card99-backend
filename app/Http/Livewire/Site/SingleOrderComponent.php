<?php

namespace App\Http\Livewire\Site;

use App\Enums\OrderStatusEnum;
use App\InterFaces\ServerInterface;
use App\Models\Balance;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SingleOrderComponent extends Component
{

    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.site.single-order-component');
    }

    public function getCode()
    {
        if ($this->order->server) {
            $server = $this->order->server;
            /** @var ServerInterface $lib */
            $lib = new $server->code;
            if ($this->order->status == OrderStatusEnum::WAITE) {
                $status = $lib->getPhoneCode($this->order);
                if ($status != OrderStatusEnum::WAITE) {
                    $this->emit('sendCode');
                }
            }
        } else {
            try {
                $respons = Http::withToken(getSettings('api_sim90'))->get('https://sms-ma.com/api/v1/orders/' . $this->order->api_id);
                if ($respons->successful() && $respons->json('status') == 'success') {
                    $this->order->update(['status' => OrderStatusEnum::COMPLETE->value, 'code' => $respons->json('order')['code']]);
                    $this->emit('sendCode');
                }
                elseif ($respons->json('status') == 'cancel') {
                    DB::beginTransaction();
                    try {
                        $this->order->update(['status' => OrderStatusEnum::CANCEL->value, 'code' => 'ملغي']);
                        Balance::create([
                            'user_id' => auth()->id(),
                            'credit' => $this->order->price,
                            'debit' => 0,
                            'info' => 'إعادة قيمة رقم '.$this->order->program_name.' - '.$this->order->country_name ,
                            'total' =>  auth()->user()->balance + $this->order->price,

                        ]);
                        DB::commit();
                        $this->emit('sendCode');
                    } catch (\Exception $e) {
                        DB::rollBack();
                    }
                }
            }catch (\Exception $ex){

            }

        }

    }
}
