<?php

namespace App\Http\Livewire;

use App\Models\Balance;
use Carbon\Carbon;
use Livewire\Component;

class LastChargeComponent extends Component
{

    public  $start;
    public  $end;
    public function mount(){
        $this->start=now()->startOfDay()->format('Y-m-d');
        $this->end=now()->startOfDay()->format('Y-m-d');

    }
    public function render()
    {

        return view('livewire.last-charge-component',[
            'balances'=>Balance::whereBetween('created_at',[Carbon::parse($this->start)->startOfDay(),Carbon::parse($this->end)->endOfDay()])->where('balances.info','like','%عن طريق المدير%')->with('user')->latest()->get()
        ]);
    }
}
