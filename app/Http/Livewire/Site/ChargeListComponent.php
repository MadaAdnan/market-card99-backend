<?php

namespace App\Http\Livewire\Site;

use App\Models\Recharge;
use Livewire\Component;

class ChargeListComponent extends Component
{
    public function render()
    {

        return view('livewire.site.charge-list-component',[
            'orders'=>Recharge::whereUserId(auth()->id())->latest()->paginate(10),
        ]);
    }
}
