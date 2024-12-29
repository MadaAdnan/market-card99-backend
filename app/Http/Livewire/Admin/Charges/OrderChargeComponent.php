<?php

namespace App\Http\Livewire\Admin\Charges;

use App\Enums\BillStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Charge;
use App\Models\Recharge;
use Livewire\Component;
use Livewire\WithPagination;

class OrderChargeComponent extends Component
{
    use WithPagination;
    public $search = '';

    public function render()
    {
        return view('livewire.admin.charges.order-charge-component', [
            'orders' => Recharge::whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->latest()->paginate(20),
        ]);
    }

    public function cancel($id)
    {
        Recharge::find($id)->update([
            'status' => BillStatusEnum::CANCEL->value
        ]);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم رفض الطلب بنجاح']);
    }

    public function complete($id)
    {
        Recharge::find($id)->update([
            'status' => BillStatusEnum::SUCCESS->value
        ]);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم قبول الطلب بنجاح']);
    }
}
