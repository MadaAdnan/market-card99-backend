<?php

namespace App\Http\Livewire\AdminCp;

use App\Enums\BillStatusEnum;
use App\Models\Bill;
use Livewire\Component;

class BellComponent extends Component
{
    public function render()
    {
        return view('livewire.admin-cp.bell-component',[
            'bill'=>Bill::whereStatus(BillStatusEnum::PENDING->value)->count(),
        ]);
    }
}
