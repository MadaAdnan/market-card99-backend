<?php

namespace App\Helpers;

use App\Enums\OrderStatusEnum;
use App\InterFaces\Price;
use App\Models\Balance;
use App\Models\Country;
use App\Models\Order;
use App\Models\Program;
use App\Models\Server;
use App\Models\Setting;
use Illuminate\Support\Str;

class Sms3t implements \App\InterFaces\ServerInterface
{

    public function getPriceApp(): Price
    {
        return new Price();
    }

    public function getPhoneNumber( Program $program): Order
    {
        $server =$program->server;
        $countryList=$program->countries;
        $countryCode=$countryList[rand(0,count($countryList)-1)]['code'];

        $network = explode(',', $server->network);
        if (is_array($network)) {
            $network = $network[rand(0, count($network) - 1)];
        }
        $url = "http://vps.sms3t.com/stubs/handler_api.php?api_key=" . $server->api . "&action=getNumber&service=" . $program->code . "&country=" . $countryCode . "&operator=" . $network;
        try {
            $response = \Http::get($url);
        }catch (\Exception|\Error $exception){
            throw new \Exception('حدث خطأ في معالجة الطلب');
        }



        if ($response->successful() && Str::contains($response->body(), 'ACCESS_NUMBER')) {
            $res = explode(':', $response->body());
            \DB::beginTransaction();
            try {
                $ratio=0;
                $price=$program->getTotalPrice();
                $setting=Setting::first();
                if(auth()->user()->user){

                    $ratio=($program->price*$setting->discount_delegate_online);
                }


                Balance::create([
                    'user_id' => auth()->id(),
                    'debit' => $price,
                    'credit' => 0,
                    'info' => 'طلب رقم ' . $program->name ,
                    'total' => auth()->user()->balance - $price,

                ]);
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'program_id' => $program->id,

                    'server_id' => $server->id,
                    'api_id' => $res[1],
                    'status' => OrderStatusEnum::WAITE->value,
                    'phone' => $res[2],
                    'price' => $price,
                    'ratio' => $ratio,
                ]);
                \DB::commit();
                return $order;
            } catch (\Exception | \Error $e) {
                \DB::rollBack();
                throw(new \Exception('حدث خطأ في معالجة الطلب'));
            }
        } else {
            throw (new \Exception('لا يوجد أرقام حاليا يرجى المحاولة لاحقا'));
        }
    }

    public function getPhoneCode(Order $order): string
    {
        $server = Server::whereCode(self::class)->first();
        $app = $server->programs()->findOrFail($order->program_id);
        $url = "http://vps.sms3t.com/stubs/handler_api.php?api_key=" . $server->api . "&action=getStatus&id=" . $order->api_id;
        try {
            $response = \Http::get($url);
        }catch (\Exception|\Error $exception){
            throw new \Exception('حدث خطأ في معالجة الطلب');
        }

        $time = now()->greaterThan($order->created_at->addMinutes(5));
        if (Str::contains($response->body(), 'STATUS_WAIT_RETRY', true)) {
            $res = explode(':', $response->body());
            \DB::beginTransaction();
            try {
                $order->update([
                    'code' => $res[1],
                    'status' => OrderStatusEnum::COMPLETE->value,
                ]);
                if ($order->user->user != null) {
                    $order->user->user->balances()->create([
                        'credit' => $order->ratio,
                        'info' => 'أرباح من شراء أرقام',
                        'total' => $order->user->user->balance + $order->ratio,
                        'ratio' => $order->ratio
                    ]);
                }
                \DB::commit();
                return OrderStatusEnum::COMPLETE->value;
            } catch (\Exception | \Error $e) {
                \DB::rollBack();
                return OrderStatusEnum::WAITE->value;
            }
        } elseif (Str::contains($response->body(), 'STATUS_WAIT_CODE') && !$time) {
            return OrderStatusEnum::WAITE->value;
        } else {
            \DB::beginTransaction();
            try {
                if ($order->status->value == OrderStatusEnum::WAITE->value) {
                    $price = $order->price;
                    if(Balance::where('info','إعادة قيمة رقم ' . $app->name.' #'.$order->id)->count()==0){
                        Balance::create([
                            'user_id' => $order->user->id,
                            'credit' => $price,
                            'debit' => 0,
                            'info' => 'إعادة قيمة رقم ' . $app->name.' #'.$order->id,
                            'total' => $order->user->balance + $price,
                        ]);
                    }
                }
                $order->update([
                    'status' => OrderStatusEnum::CANCEL->value,
                    'code' => 'ملغي'
                ]);

                \DB::commit();
                return OrderStatusEnum::CANCEL->value;
            } catch (\Exception | \Error $e) {
                \DB::rollBack();
                throw (new \Exception('حدث خطأ في معالجة الطلب'));
            }
        }
    }

    public function cancelOrder(Order $order): string
    {
        \DB::beginTransaction();
        try {
            if ($order->status->value == OrderStatusEnum::WAITE->value) {
                $price=$order->price;
                if(Balance::where('info','إعادة قيمة رقم ' . $order->program?->name.' #'.$order->id)->count()==0){
                    Balance::create([
                        'user_id' => $order->user->id,
                        'credit' => $price,
                        'debit' => 0,
                        'info' => 'إعادة قيمة رقم ' . $order->program?->name.' #'.$order->program?->id,
                        'total' => $order->user->balance + $price,
                    ]);
                }


            }
            $order->update([
                'status' => OrderStatusEnum::CANCEL->value,
                'code' => 'ملغي'
            ]);

            \DB::commit();
            return OrderStatusEnum::CANCEL->value;
        } catch (\Exception | \Error $e) {
            \DB::rollBack();
            throw (new \Exception('حدث خطأ في معالجة الطلب'));
        }
    }

    public function GetBalance(): mixed
    {
        return 0.0;
    }
}
