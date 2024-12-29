<?php

namespace App\Http\Livewire\Site;

use App\InterFaces\ServerInterface;
use App\Models\Country;
use App\Models\Program;
use App\Models\Server;
use Livewire\Component;

class BuyTableComponent extends Component
{

    public $server_id;
    public $country_id;
    public $apps = [];
    protected $listeners = ['selectServer', 'selectCountry'];

    public function render()
    {

        return view('livewire.site.buy-table-component');
    }

    public function selectServer($event)
    {
        //dd($event);
        $this->server_id = $event['server_id'];
        $this->country_id = null;
        $this->apps = Server::find($this->server_id)->programs;
        // dd($this->apps[0]->pivot->price);
    }

    public function selectCountry($event)
    {
        $this->country_id = $event['country_id'];
        $this->apps = Server::find( $event['server_id'])->programs;
        // dd($this->server_id);
    }

    public function buy($app_id)
    {
        $server = Server::findOrFail($this->server_id);
        $this->apps = $server->programs;
        try {

            if (auth()->check() && auth()->user()->orders()->orderWait()->count() > 9) {
                $this->dispatchBrowserEvent('error', ['msg' => 'يرجى الإنتظار لإنتهاء الأرقام المطلوبة ثم المحاولة مرة أخرى']);
            } else {

                /** @var ServerInterface $lib */
                $lib = new $server->code;
                $country = Country::findOrFail($this->country_id);
                $app = $server->programs()->findOrFail($app_id);
                if (auth()->check() && auth()->user()->balance >= $app->pivot->price) {
                    $order = $lib->getPhoneNumber($country, $app);
                    $this->emit('buyOrder');

                } else {
                    $this->dispatchBrowserEvent('error', ['msg' => 'لا تملك رصيد كاف لإتمام العملية']);
                }

            }



        } catch (\Exception|\Error $e) {
           $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
        }

    }


}
