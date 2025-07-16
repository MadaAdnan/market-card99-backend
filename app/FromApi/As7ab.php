<?php

namespace App\FromApi;

use App\Jobs\OneSignalJob;
use App\Models\Bill;
use App\Rpositories\BillRepository;

class As7ab implements PayByApi
{


    private $setting;

    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    public function buyFromApiFree(Bill $bill, $id = null): Bill
    {
        $code = $bill->id_bill;
        if ($code == null) {
            return $bill;
        }

        $url = 'https://as7abcard.com/api/v1/createOrder/';
        $data = ['orderToken' => $bill->id_bill];
        $data ['args'] = ['playerid'=>$bill->customer_id];
        $data['items'] =[ ['denomination_id' => $bill->product->codes_api['as7ab'], 'qty' => 1,'amount'=>$bill->amount]];
        $token = $this->setting->apis['as7ab'];
        $bill->save();
        try {
            $data=json_encode($data);
            $response = \Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json'
            ])->withBody($data,'application/json')->timeout(120)->post($url);


            if ($response->successful() && \Str::lower($response->json('result')) == 'success' && isset($response->json()['orderid'])) {
                $bill->api = $bill->product->api;
                $bill->api_id = $response->json()['orderid'];
            }
            return $bill;
        } catch (\Exception | \Error $exception) {
            return $bill;
        }
    }

    public function buyFromApiFixed(Bill $bill, $id = null): Bill
    {

        $code = $bill->id_bill;
        if ($code == null) {
            return $bill;
        }
        $url = 'https://as7abcard.com/api/v1/createOrder/';
        $data = [];
        $data ['orderToken'] = $code;
        $data ['args'] = ['playerid'=>$bill->customer_id];
        $data['items'] =[ ['denomination_id' => $bill->product->codes_api['as7ab'], 'qty' =>1]];
        $token = $this->setting->apis['as7ab'];
        $bill->save();
        try {
            $data=json_encode($data);
            $response = \Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json'
            ])->withBody($data,'application/json')->timeout(120)->post($url);

            if ($response->successful() && \Str::lower($response->json('result')) === 'success' && isset($response->json()['orderid'])) {
                $bill->api = $bill->product->api;
                $bill->api_id = $response->json()['orderid'];
            }

            return $bill;
        } catch (\Exception | \Error $e) {
            return $bill;
        }
    }

    public function checkStatus(Bill $bill): Bill
    {
        $url = 'https://as7abcard.com/api/v1/bulkOrderStatus';
        $token = $this->setting->apis['as7ab'];
        $data = ['orderIds' => [$bill->api_id]];
        try {
            $response = \Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json'
            ])->withBody(json_encode($data), 'application/json')->post($url);

            if ($response->successful() && \Str::lower($response->json('result')) == 'success') {
                if (isset($response->json('orders')[$bill->api_id]['order_status']) && $response->json('orders')[$bill->api_id]['order_status'] == 'completed') {
                    $msg_cancel = '';
                    if (isset($response->json('data')[0]['replay_api'][0])) {
                        $msg_cancel = $response->json('data')[0]['replay_api'][0];
                        try {
                            $data = ['body' => $msg_cancel];
                            $job = new OneSignalJob($bill->user->email, $data);
                            dispatch($job);
                        } catch (\Exception | \Error $e) {
                        }
                    }
                    BillRepository::complateBill($bill, $msg_cancel);
                } elseif (isset($response->json('orders')[$bill->api_id]['order_status']) && $response->json('orders')[$bill->api_id]['order_status'] !== 'completed' && $response->json('orders')[$bill->api_id]['order_status'] !== 'processing') {
                    $msg_cancel = '';
                    if (isset($response->json('data')[0]['replay_api'][0])) {
                        $msg_cancel = $response->json('data')[0]['replay_api'][0];
                        try {
                            $data = ['body' => $msg_cancel];
                            $job = new OneSignalJob($bill->user->email, $data);
                            dispatch($job);
                        } catch (\Exception | \Error $e) {
                        }
                    }
                    BillRepository::cancelBill($bill, $msg_cancel);
                }
            }
            return $bill;
        } catch (\Exception | \Error $e) {
            return $bill;
        }
    }

    public function cancelBill(Bill $bill, $other_data = null): Bill
    {
        BillRepository::cancelBill($bill, $other_data);
        return $bill;
    }

    public function getPlayerName($playerId, $game)
    {
        $url = 'https://as7abcard.com/api/v1/getPlayerName/?playerid=' . $playerId . '&game=' . $game;
        $token = $this->setting->apis['as7ab'];
        try {
            $response = \Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json'
            ])->get($url);
            if ($response->successful() && \Str::lower($response->json('result')) == 'success') {
                $name=$response->json('playername');
                return str_replace('as7abcard','',strtolower($name));
            } elseif (\Str::lower($response->json('result')) == 'error') {
                return 'تأكد من رقم اللاعب';
//                return $playerId.' - '.$game;
            }
        } catch (\Exception | \Error $e) {
            throw new \Exception('حدث خطأ في الطلب يرجى المحاولة لاحقا');
        }

    }



}
