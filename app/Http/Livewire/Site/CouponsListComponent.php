<?php

namespace App\Http\Livewire\Site;

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CouponsListComponent extends Component
{
    public function render()
    {
        return view('livewire.site.coupons-list-component', [
            'coupons' => Coupon::whereNull('user_id')->where('status', true)->groupBy('price')->orderByDesc('price','asc')->get()->sortBy('price'),
            'couponsList' => Coupon::where('user_id',auth()->id())->where('status', true)->latest()->paginate(25)
        ]);
    }

    public function buy($id)
    {
        DB::beginTransaction();
        try{

            $coupon = Coupon::whereNull('user_id')->where('status', true)->find($id);
            if (auth()->check() && auth()->user()->balance < $coupon->price) {
                throw new \Exception( 'ليس لديك رصيد كافي لشراء هذا الكوبون');
            }
            $coupon->update([
                'user_id'=>auth()->id(),
            ]);
            auth()->user()->balances()->create([
                'debit' => $coupon->price,
                'credit' => 0,
                'info' => 'شراء كوبون رقم '.$coupon->id,
                'total' => auth()->user()->balance-$coupon->price,
            ]);
            DB::commit();
            $this->dispatchBrowserEvent('success', ['msg' =>'تم شراء الكوبون بنجاح']);
        }catch (\Exception $e){
            DB::rollBack();
            $this->dispatchBrowserEvent('error', ['msg' =>$e->getMessage()]);
        }


    }
}
