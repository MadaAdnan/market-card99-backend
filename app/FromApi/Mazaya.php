<?php

namespace App\FromApi;

use App\Enums\BillStatusEnum;
use App\Jobs\OneSignalJob;
use App\Models\Bill;
use App\Rpositories\BillRepository;

class Mazaya implements PayByApi
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

        $url = 'https://store.mazaya-online.com/api/v1/bills/';
        $data = [
            'product_id' => $bill->product->codes_api['mazaya'],
            'qty' => $bill->amount,
            'player_id' => $bill->customer_id,
            'uuid' => $bill->id_bill,
            'player_name' => $bill->customer_name,
        ];

        $token = $this->setting->apis['mazaya'];
        $bill->save();
        try {
            $response = \Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json'
            ])->withBody(json_encode($data), 'application/json')->timeout(120)->post($url);

            sleep(1);
            if ($response->successful() && isset($response->json()['order'])) {
                $bill->api = $bill->product->api;
                $bill->api_id = $response->json()['order']['id'];
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

        $url = 'https://store.mazaya-online.com/api/v1/bills';
        $data = [
            'product_id' => $bill->product->codes_api['mazaya'],
            'qty' => 1,
            'player_id' => $bill->customer_id,
            'uuid' => $bill->id_bill,
            'player_name' => $bill->customer_name,
        ];

        $token = $this->setting->apis['mazaya'];
        $bill->save();
        try {
            $response = \Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json'
            ])->timeout(120)->post($url,$data);

            sleep(1);
            if ($response->successful() && isset($response->json()['order'])) {
                $bill->api = $bill->product->api;
                $bill->api_id = $response->json()['order']['id'];
            }
            return $bill;
        } catch (\Exception | \Error $exception) {
            return $bill;
        }
    }

    public function checkStatus(Bill $bill): Bill
    {
        $url = 'https://store.mazaya-online.com/api/v1/bills/' . $bill->api_id;
        $token = $this->setting->apis['mazaya'];

        try {
            $response = \Http::withToken($token)->timeout(60)->get($url);
            if ($response->successful() && isset($response->json()['order'])) {

                if ($response->json('order')['status'] == 'complete' ) {
                    $msg_cancel = '';
                    if (isset($response->json('order')['admin_data']['msg'])) {
                        $msg_cancel = $response->json('order')['admin_data']['msg'];
                        try {
                            $data = ['body' => $msg_cancel];
                            $job = new OneSignalJob($bill->user->email, $data);
                            dispatch($job);
                        } catch (\Exception | \Error $e) {
                        }
                    }
                    BillRepository::complateBill($bill, $msg_cancel);
                } elseif ($response->json('order')['status'] == 'canceled' ) {
                    $msg_cancel = '';
                    if (isset($response->json('order')['admin_data']['msg'])) {
                        $msg_cancel = $response->json('order')['admin_data']['msg'];
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
                $name = $response->json('playername');
                return str_replace('as7abcard', '', strtolower($name));
            } elseif (\Str::lower($response->json('result')) == 'error') {
                return 'تأكد من رقم اللاعب';
//                return $playerId.' - '.$game;
            }
        } catch (\Exception | \Error $e) {
            throw new \Exception('حدث خطأ في الطلب يرجى المحاولة لاحقا');
        }

    }


}
