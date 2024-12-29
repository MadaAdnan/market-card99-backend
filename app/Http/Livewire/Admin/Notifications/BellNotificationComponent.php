<?php

namespace App\Http\Livewire\Admin\Notifications;

use App\Enums\BillStatusEnum;
use App\Models\Bill;
use Livewire\Component;

class BellNotificationComponent extends Component
{
   public $bills=[];
    public function render()
    {
        $this->bills=Bill::where(function ($query){
            $query->where('status',BillStatusEnum::REQUEST_CANCEL);
            $query->orWhere('status',BillStatusEnum::PENDING);
        })->latest()->get();
        return view('livewire.admin.notifications.bell-notification-component',);
    }

    public function test(){
        $this->bills=Bill::where(function ($query){
            $query->where('status',BillStatusEnum::REQUEST_CANCEL);
            $query->orWhere('status',BillStatusEnum::PENDING);
        })->latest()->get();
        if($this->bills->count()>0){
            $this->dispatchBrowserEvent('test');
        }
    }




}
