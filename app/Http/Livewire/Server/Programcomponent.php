<?php

namespace App\Http\Livewire\Server;

use App\InterFaces\ServerInterface;
use App\Models\Country;
use App\Models\Order;
use App\Models\Server;
use App\Models\Setting;
use Livewire\Component;

class Programcomponent extends Component
{

    public Server $server;
    public Country $country;
    public Order $order;
    public $order_count = 0;
    public $app;
    /**
     * @var Setting $setting
     */
    public  $setting;

    public function mount(Server $server, Country $country)
    {
        $this->server = $server;
        $this->country = $country;
        $this->setting=Setting::first();
    }

    public function render()
    {
        if($this->setting==null){
            $this->setting=Setting::first();
        }

        return view('livewire.server.programcomponent', [
            'programs' => $this->server->programs,
        ]);
    }

    public function buy($app_id)
    {


        try {

            if (auth()->check() && auth()->user()->orders()->orderWait()->count() > 9) {
                $this->dispatchBrowserEvent('error', ['msg' => 'يرجى الإنتظار لإنتهاء الأرقام المطلوبة ثم المحاولة مرة أخرى']);
            } else {

                /** @var ServerInterface $lib */
                $lib = new $this->server->code;
                $app = $this->server->programs()->findOrFail($app_id);
                $this->app=$app;
                $price = $app->pivot->price;
                if (auth()->user()->hasRole('partner')) {
                    $price = $app->pivot->price - ($app->pivot->price *$this->setting->discount_delegate_online);
                }
                if (auth()->check() && auth()->user()->balance >= $price) {
                    $this->order = $lib->getPhoneNumber($this->country, $app);
                    $this->order_count=0;
                    $this->emit('buyOrder');
                } else {
                    $this->dispatchBrowserEvent('error', ['msg' => 'لا تملك رصيد كاف لإتمام العملية']);
                }
            }
        } catch (\Exception | \Error $e) {
            $this->order_count++;
            if ($this->order_count < 6) {
               // info($this->order_count);
                $this->buy($app_id);
            } else {
                $this->order_count=0;
                $this->dispatchBrowserEvent('error', ['msg' => $e->getMessage()]);
            }
        }

    }
}
