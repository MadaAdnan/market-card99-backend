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

class Acktiwater implements ServerInterface
{

    public function getPriceApp(): Price
    {
        // TODO: Implement getPriceApp() method.
    }

    public function getPhoneNumber( Program $program): Order
    {
        $server = $program->server;


        $countryList=$program->countries;
        $countryCode=$countryList[rand(0,count($countryList)-1)]['code'];

        $response = \Http::get('https://sms-acktiwator.ru/api/getnumber/' . $server->api . '?id=' . $program->code . '&code=' . $countryCode);
        if ($response->successful() && $response->json('send') == 0) {
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
                    'api_id' => $response->json('id'),
                    'status' => OrderStatusEnum::WAITE->value,
                    'phone' => $response->json('number'),
                    'ratio'=>$ratio,
                    'price' =>$price,
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
        $response = \Http::get('https://sms-acktiwator.ru/api/getstatus/' . $server->api . '?id=' . $order->api_id);
        $time = now()->greaterThan($order->created_at->addMinutes(5));
        if ($response->successful() && isset($response->json()['small']) && $response->json('small') != '') {
            $order->update([
                'code' => $response->json('small'),
                'status' => OrderStatusEnum::COMPLETE->value,
            ]);
            return $order->status->value;
        }
        elseif ($time) {

            \DB::beginTransaction();
            try {
                if ($order->status->value == OrderStatusEnum::WAITE->value) {
                    $price=$order->price;
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
        } else {
            return OrderStatusEnum::WAITE->value;
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

    }
}
