<?php

namespace App\Http\Livewire\Site;

use Livewire\Component;

class UserBalanceComponent extends Component
{

    protected $listeners=['buyOrder'=>'$refresh'];
    public function render()
    {
        return view('livewire.site.user-balance-component',[
            'balance'=>auth()->user()->balance,
        ]);
    }
}
