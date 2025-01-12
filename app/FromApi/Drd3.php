<?php

namespace App\FromApi;

use App\Models\Bill;
use App\Rpositories\BillRepository;

class Drd3 implements PayByApi
{
    private $setting;

    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    public function buyFromApiFree(Bill $bill, $code=null): Bill
    {
        $url = 'https://drd3m.me/api/v2';
        $token = $this->setting->apis['drd3'];
        $data = [
            'key' => $token,
            'action' => 'add',
            'service' => $bill->product->codes_api['drd3'],
            'link' => $bill->customer_id,
            'quantity' => $bill->amount,
        ];
        $bill->save();
        try {
            $response = \Http::post($url, $data);
            if($response->successful()){
                $bill->update([
                    'api_id'=>$response->json('order'),
                    'api'=> $bill->product->api
                ]);
            }
            return $bill;
        } catch (\Exception | \Error $e) {
            return $bill;
        }

    }

    public function buyFromApiFixed(Bill $bill, $code=null): Bill
    {
        $url = 'https://drd3m.me/api/v2';
        $token = $this->setting->apis['drd3'];
        $data = [
            'key' => $token,
            'action' => 'add',
            'service' => $bill->product->codes_api['drd3'],
            'link' => $bill->customer_id,
            'quantity' => $bill->product->count,
        ];
        $bill->save();
        try {
            $response = \Http::post($url, $data);

            if($response->successful()){
                $bill->update([
                    'api_id'=>$response->json('order'),
                    'api'=> $bill->product->api
                ]);
            }
            return $bill;
        } catch (\Exception | \Error $e) {
            return $bill;
        }
    }

    public function checkStatus(Bill $bill): Bill
    {
        $url = 'https://drd3m.me/api/v2';
        $token = $this->setting->apis['drd3'];
        $data = [
            'key' => $token,
            'action' => 'status',
            'order' => $bill->api_id,
        ];
        try {
            $response = \Http::post($url, $data);
//info("DRD3");
//info($response->body());
            if($response->successful() && strtolower($response->json('status'))==strtolower('Completed')){
                BillRepository::complateBill($bill);

            }elseif ($response->successful() && strtolower($response->json('status'))==strtolower('Rejected')){
                BillRepository::cancelBill($bill);
            }
            return $bill;
        } catch (\Exception | \Error $e) {
            return $bill;
        }
    }

    public function cancelBill(Bill $bill, $other_data = null): Bill
    {
        BillRepository::cancelBill($bill,$other_data);
        return $bill;
    }
}
