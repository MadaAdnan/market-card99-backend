<?php

namespace App\Helpers;

use App\Enums\OrderStatusEnum;
use App\InterFaces\Price;
use App\InterFaces\ServerInterface;
use App\Models\Balance;
use App\Models\Country;
use App\Models\Order;
use App\Models\Program;
use App\Models\Server;
use App\Models\Setting;

class SmsActivate implements ServerInterface
{

    public function getPriceApp(): Price
    {
       return new Price();
    }

    public function getPhoneNumber( Program $program): Order
    {
        $server = $program->server;


        $countryList=$program->countries;
        $countryCode=$countryList[rand(0,count($countryList)-1)]['code'];
        $response = \Http::get('https://sms-activate.org/stubs/handler_api.php?api_key=' . $server->api . '&action=getNumberV2&service=' . $program->code . '&country=' . $countryCode);
        if ($response->successful() && isset($response->json()['activationId'])) {
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
                    'api_id' => $response->json('activationId'),
                    'status' => OrderStatusEnum::WAITE->value,
                    'phone' => $response->json('phoneNumber'),
                    'price' => $price,
                    'ratio' => $ratio,
                ]);
                \DB::commit();
                return $order;
            } catch (\Exception | \Error $e) {
                \DB::rollBack();
                throw(new \Exception('حدث خطأ في معالجة الطلب' ));
            }
        } else {
            throw (new \Exception('لا يوجد أرقام حاليا يرجى المحاولة لاحقا'));
        }
    }

    public function getPhoneCode(Order $order): string
    {
        $server = Server::whereCode(self::class)->first();
        $app = $server->programs()->findOrFail($order->program_id);
        //$response = \Http::get('https://sms-acktiwator.ru/api/getstatus/' . $server->api . '?id=' . $order->api_id );
        $response = \Http::get('https://api.sms-activate.org/stubs/handler_api.php?api_key=' . $server->api . '&action=getStatus&id=' . $order->api_id);
        $time = now()->greaterThan($order->created_at->addMinutes(3));
        if ($response->successful() && \Str::contains($response->body(), 'STATUS_OK')) {
            $code_array = explode(':', $response->body());
           // $code=explode('-',\Str::replace('?','',$code_array[1]));
           /* if(count($code)>1){
                $code=$code[1].\Str::substr(0,3,$code[0]);
            }else{
                $code=$code_array[1];
            }*/
            if (isset($code_array[1])) {
                $order->update([
                    'code' => $response->body(),
                    'status' => OrderStatusEnum::COMPLETE->value,
                ]);
                return $order->status->value;
            } else {
                return OrderStatusEnum::WAITE->value;
            }

        } elseif (\Str::contains($response->body(), 'STATUS_WAIT_CODE')) {

            if ($time) {

                \DB::beginTransaction();
                try {
                    if ($order->status == OrderStatusEnum::WAITE) {
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
            return OrderStatusEnum::WAITE->value;
        } else {
            \DB::beginTransaction();
            try {
                if ($order->status == OrderStatusEnum::WAITE) {
                    $price=$order->price;

                    Balance::create([
                        'user_id' => $order->user->id,
                        'credit' => $price,
                        'debit' => 0,
                        'info' => 'إعادة قيمة رقم ' . $app->name,
                        'total' => $order->user->balance + $price,
                    ]);
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
