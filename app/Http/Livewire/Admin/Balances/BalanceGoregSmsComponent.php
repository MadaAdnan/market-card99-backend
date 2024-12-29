<?php

namespace App\Http\Livewire\Admin\Balances;

use App\Helpers\GoregSms;
use App\InterFaces\ServerInterface;
use App\Models\Server;
use Livewire\Component;

class BalanceGoregSmsComponent extends Component
{

    public Server $server;
    public $balance=0;
    public function mount(Server $server=null){
        $this->server=Server::where('code',GoregSms::class)->first();
        try {
            /** @var ServerInterface $lib */
            $lib=new $this->server->code;
            $this->balance=$lib->GetBalance();
        }catch (\Exception $e){}
    }
    public function render()
    {
        return view('livewire.admin.balances.balance-goreg-sms-component');
    }
}
