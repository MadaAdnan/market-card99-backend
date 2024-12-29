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

class Viotp implements \App\InterFaces\ServerInterface
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

        try {
            $response = \Http::get("https://api.viotp.com/request/getv2?token=" . $server->api . "&serviceId=" . $program->code . "&country=vn");
        } catch (\Exception $e) {
            throw(new \Exception('حدث خطأ في معالجة الطلب' ));
        }


        if ($response->successful() && $response->json('status_code') == 200) {
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
                    'info' => 'طلب رقم ' . $program->name,
                    'total' => auth()->user()->balance - $price,

                ]);
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'program_id' => $program->id,

                    'server_id' => $server->id,
                    'api_id' => $response->json('data')['request_id'],
                    'status' => OrderStatusEnum::WAITE->value,
                    'phone' => $response->json('data')['phone_number'],
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
        $server =Server::find($order->server_id);
        $app =Program::find($order->program_id);
        $url="https://api.viotp.com/session/getv2?requestId=" . $order->api_id . "&token=" . $server->api;
        $response = \Http::get($url);
        $time = now()->greaterThan($order->created_at->addMinutes(5));
        if ($response->successful() && $response->json('status_code') == 200 && $response->json('data')['Status'] == 1) {
            $order->update([
                'code' => $response->json('data')['Code'],
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
            return OrderStatusEnum::COMPLETE->value;
        }elseif($response->json('status_code') == -2){
           return  $this->cancelOrder($order);
        }
        elseif ($response->json('data')['Status'] == 0 && !$time ) {
            return OrderStatusEnum::WAITE->value;
        } else {
           return  $this->cancelOrder($order);
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
            info('Error Vitop : '.$e->getMessage());
            throw (new \Exception('حدث خطأ في معالجة الطلب'));
        }
    }

    public function GetBalance(): mixed
    {
        return 0.0;
    }
}
