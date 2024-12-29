<?php

namespace App\Http\Livewire\Site;

use App\Enums\OrderStatusEnum;
use App\Models\Balance;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Sim90ApiComponent extends Component
{
    public $baseUrl = 'https://sms-ma.com/api/v1/';
    public $servers = [];
    public $server;
    public $server_id;

    public $countries = [];
    public $country;
    public $country_id;
    public $apps = [];
    // public $app;
    public $app_id;

    public function mount()
    {

        $response = Http::withToken(getSettings('api_sim90'))->get($this->baseUrl . 'servers');
        if ($response->successful() && $response->json('status') == 'success') {
            //dd($response->json());
            $this->servers = collect($response->json('servers'));
        } else {
            $this->dispatchBrowserEvent('error', ['msg' => 'خطأ في السيرفر']);
        }
    }

    public function render()
    {
        return view('livewire.site.sim90-api-component');
    }

    public function updatedServerId()
    {
        $this->server = array_values(array_filter($this->servers->filter(function ($serv) {
            if ($serv['id'] == $this->server_id)
                return $serv;
        })->toArray()));
        $this->country=null;
        $this->country_id=null;
        $this->countries=[];
        $this->app_id=null;
        $this->apps=[];
        $this->apps=null;


        $this->countries = $this->server[0]['countries'];
        $this->apps = $this->server[0]['programs'];
    }


    public function changeCountry($country_id)
    {
        $this->country_id = $country_id;
        $this->country = array_values(array_filter(collect($this->countries)->filter(function ($country) {
            if ($country['id'] == $this->country_id)
                return $country;
        })->toArray()));
        // dd($this->country);
    }

    /* public function changeApp($app_id){
         $this->app_id=$app_id;
         $this->app=array_values(array_filter($this->apps->filter(function($app){
             if( $app['id']==$this->app_id)
                 return $app;
         })->toArray()));
         // dd($this->country);
     }*/

    public function buyNumber($app_id)
    {
        $this->app_id = $app_id;
        $this->app = array_values(array_filter(collect($this->apps)->filter(function ($app) {
            if ($app['id'] == $this->app_id)
                return $app;
        })->toArray()));
        $price = $this->app[0]['price']+($this->app[0]['price']*getSettings('win_sim90_ratio')??0);
        if (auth()->user()->balance < $price) {
            $this->dispatchBrowserEvent('error', ['msg' => 'لا تملك رصيد كافي ']);
            return;
        }


        if ($this->server_id == null) {
            $this->dispatchBrowserEvent('error', ['msg' => 'يرجى تحديد السيرفر بشكل صحيح']);
            return;
        }
        if ($this->country_id == null) {
            $this->dispatchBrowserEvent('error', ['msg' => 'يرجى تحديد الدولة بشكل صحيح']);
            return;
        }
        if ($this->app_id == null) {
            $this->dispatchBrowserEvent('error', ['msg' => 'يرجى تحديد التطبيق بشكل صحيح']);
            return;
        }

        try {

            $response = Http::withToken(getSettings('api_sim90'))
                ->get($this->baseUrl . 'order/store?server_id=' . $this->server_id . ' &country_id=' . $this->country_id . '&program_id=' . $this->app_id);
            if ($response->successful() && $response->json('status') == 'success') {
              // dd($response->json('order'));
                \DB::beginTransaction();
                try {

                    Balance::create([
                        'user_id' => auth()->id(),
                        'debit' => $price,
                        'credit' => 0,
                        'info' => 'طلب رقم ' . $response->json('order')['app'] . ' من دولة ' . $response->json('order')['country'],
                        'total' =>  auth()->user()->balance - $price,

                    ]);
                    $order = Order::create([
                        'user_id' => auth()->id(),
                        'program_name' =>$response->json('order')['app'],
                        'country_name' => $response->json('order')['country'],
                        'api_id' => $response->json('order')['id'],
                        'status' => OrderStatusEnum::WAITE->value,
                        'phone' => $response->json('order')['phone'],
                        'price' => $price,
                        'cost'=>$this->app[0]['price'],
                    ]);
                    \DB::commit();
                   $this->redirectRoute('online.orders');
                } catch (\Exception | \Error $e) {
                    \DB::rollBack();
                    throw(new \Exception('حدث خطأ في معالجة الطلب' . $e->getMessage()));
                }

            }
            else {
                throw (new \Exception($response->json('msg')));
            }

        } catch (\Exception | \Error $e) {
            //dd($e);
            $this->dispatchBrowserEvent('error', ['msg' => 'خطأ في السيرفر يرجى المحاولة لاحقا']);
        }
    }
}
