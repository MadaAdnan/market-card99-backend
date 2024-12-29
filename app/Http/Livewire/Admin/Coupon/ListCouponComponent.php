<?php

namespace App\Http\Livewire\Admin\Coupon;

use App\Models\Coupon;
use Livewire\Component;
use Livewire\WithPagination;

class ListCouponComponent extends Component
{
    use WithPagination;
    public $search='';
    public function render()
    {
        return view('livewire.admin.coupon.list-coupon-component',[
            'coupons'=>Coupon::where('code','like','%'.$this->search.'%')->latest()->paginate(20),
        ]);
    }
}
