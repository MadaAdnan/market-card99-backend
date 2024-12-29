<?php

namespace App\Http\Livewire\Admin\Coupon;

use App\Models\Coupon;
use Livewire\Component;

class CreateCouponComponent extends Component
{
    public $count = 1;
    public $price = 10;

    public function render()
    {
        return view('livewire.admin.coupon.create-coupon-component');
    }

    public function submit()
    {
        $this->validate([
            'count' => 'required|numeric|gt:0',
            'price' => 'required|numeric|gt:0',
        ]);

        for ($i = 0; $i < $this->count; $i++) {
            Coupon::firstOrCreate([
                'code' => \Str::upper(\Str::random(8)),
            ], [
                'price' => $this->price,
                'status' => true,
            ]);
        }
        $this->dispatchBrowserEvent('success', ['msg' => 'تم إضافة الأكواد بنجاح']);
        $this->reset();

    }
}
