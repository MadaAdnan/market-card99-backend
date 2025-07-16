<?php

namespace App\FromApi;

use App\Enums\BillStatusEnum;
use App\Jobs\OneSignalJob;
use App\Models\Bill;
use App\Rpositories\BillRepository;

class CachBack implements PayByApi
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
        $url = 'https://api.cashback-card.net/client/api/newOrder/' . $bill->product->codes_api['cache-back'] . '/params?qty=' . $bill->amount . '&order_uuid=' . $code . '&playerID=' . $bill->customer_id;
        $token = $this->setting->apis['cache-back'];

        $bill->save();
        try {
            $response = \Http::withHeaders([
                'api-token' => $token
            ])->get($url);


            if ($response->successful() && \Str::lower($response->json('status')) == 'ok' && $response->json('data')['status'] != 'not_available') {
                $bill->api = $bill->product->api;
                $bill->api_id = $response->json('data')['order_id'];
            }//


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
        $url = 'https://api.cashback-card.net/client/api/newOrder/' . $bill->product->codes_api['cache-back'] . '/params?qty=' . $bill->product->count . '&order_uuid=' . $code . '&playerID=' . $bill->customer_id;

        $token = $this->setting->apis['cache-back'];
        $bill->save();
        try {
            $response = \Http::withHeaders([
                'api-token' => $token
            ])->get($url);


            if (($response->successful() && \Str::lower($response->json('status')) == 'ok') || $response->json('data')['status'] != 'not_available') {
                $bill->api = $bill->product->api;
                $bill->api_id = $response->json('data')['order_id'];
            }
            return $bill;
        } catch (\Exception | \Error $e) {

            return $bill;
        }
    }

    public function checkStatus(Bill $bill): Bill
    {
        $url = 'https://api.cashback-card.net/client/api/check?orders=[' . $bill->api_id . ']';
        $token = $this->setting->apis['cache-back'];
        $response = \Http::withHeaders([
            'api-token' => $token
        ])->get($url);

        if ($response->successful() && \Str::lower($response->json('status')) == 'ok') {
            if (isset($response->json('data')[0]['status']) && $response->json('data')[0]['status'] == 'accept') {
                $msg_cancel = '';
                if (isset($response->json('data')[0]['replay_api'][0])) {
                    $msg_cancel = $response->json('data')[0]['replay_api'][0];
                    try{
                        $data = ['body' => $msg_cancel];
                        $job = new OneSignalJob($bill->user->email, $data);
                        dispatch($job);
                    }catch (\Exception|\Error $e){}
                }
                BillRepository::complateBill($bill, $msg_cancel);
            } elseif (isset($response->json('data')[0]['status']) && $response->json('data')[0]['status'] == 'reject') {
                $msg_cancel = '';
                if (isset($response->json('data')[0]['replay_api'][0])) {
                    $msg_cancel = $response->json('data')[0]['replay_api'][0];
                    try{
                        $data = ['body' => $msg_cancel];
                        $job = new OneSignalJob($bill->user->email, $data);
                        dispatch($job);
                    }catch (\Exception|\Error $e){}

                }
                BillRepository::cancelBill($bill, $msg_cancel);
            }
        }
        return $bill;
    }

    public function cancelBill(Bill $bill, $other_data = null): Bill
    {
        BillRepository::cancelBill($bill, $other_data);
        return $bill;
    }
}
