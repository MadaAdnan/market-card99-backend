<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class ChargeCountComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.charge-count-component',[
            'count'=> $count=\App\Models\Recharge::where('status',\App\Enums\BillStatusEnum::PENDING->value)->count(),
        ]);
    }
}
