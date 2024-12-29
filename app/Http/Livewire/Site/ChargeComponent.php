<?php

namespace App\Http\Livewire\Site;

use App\Enums\BillStatusEnum;
use App\Models\Balance;
use App\Models\Bank;
use App\Models\Coupon;
use App\Models\Recharge;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChargeComponent extends Component
{
    use WithFileUploads;

    public $img;
    public $code;
    public $value;
    public $info;
    public $bank_id;
    public $is_coupon = false;
    public ?Bank $bank;

    public function render()
    {
        return view('livewire.site.charge-component', [
            'banks' => Bank::whereIsActive(true)->orderBy('sort')->get()
        ]);
    }

    public function changeBank(Bank $bank)
    {
        $this->is_coupon = false;
        $this->bank = $bank;
        $this->bank_id = $bank->id;

    }

    public function submit()
    {

        $this->validate([
            'img' => 'required|image',
            'bank_id' => 'required',
            'value' => 'required'
        ]);
        Recharge::create([
            'info' => $this->info,
            'value' => $this->value,
            'user_id' => auth()->id(),
            'bank_id' => $this->bank->id,
            'img' => \Storage::disk('public')->put('charge', $this->img),
            'status'=>BillStatusEnum::PENDING->value
        ]);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم إرسال طلب الشحن سيتم مراجعة طلبك في أقرب وقت']);
        $this->reset(['img', 'value', 'info']);
    }

    public function IsCoupon()
    {
        $this->is_coupon = true;
        $this->bank = null;

    }

    public function chargeCode()
    {
        $this->validate([
            'code' => 'required',

        ]);

        $coupon = Coupon::whereStatus(true)->whereCode(Str::upper($this->code))->first();

        if ($coupon) {
            \DB::beginTransaction();
            try {
                $data=[
                    'status' => false,

                ];
                if($coupon->user_id==null){
                    $data['user_id']= auth()->id();

                }
                $coupon->update($data);
                Balance::create([
                    'credit' => $coupon->price,
                    'debit' => 0,
                    'user_id'=>auth()->id(),
                    'info' => 'شحن عن طريق الكوبون رقم : ' . $coupon->code,
                    'total' => auth()->user()->balance + $coupon->price,
                ]);
                \DB::commit();
                $this->dispatchBrowserEvent('success', ['msg' => 'تم شحن الحساب بنجاح']);

            } catch (\Exception $e) {
                \DB::rollBack();
                $this->dispatchBrowserEvent('error', ['msg' => $e->getMessage()]);
            }
        } else {
            $this->dispatchBrowserEvent('error', ['msg' => 'الكوبون غير موجود أو انه تم إستخدامه']);
        }
    }
}
