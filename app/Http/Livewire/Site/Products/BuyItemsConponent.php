<?php

namespace App\Http\Livewire\Site\Products;

use App\Enums\BillStatusEnum;
use App\Enums\CurrencyEnum;
use App\Models\Bill;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Point;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;

class BuyItemsConponent extends Component
{
    public $count = 1;
    public Product $product;

    public function render()
    {
        return view('livewire.site.products.buy-items-conponent');
    }

    public function increase()
    {
        $this->count++;
    }

    public function decrease()
    {
        if ($this->count > 1)
            $this->count--;
    }

    public function submit()
    {

        try {
            if(!$this->product->is_available || !$this->product->active || !$this->product->category->active){
                throw new \Exception('المنتج غير متوفر حاليا');
            }
            if (!auth()->check()) {
                throw new \Exception('يرجى تسجيل الدخول قبل عملية الشراء');
            }
            if ($this->count <= 0) {
                throw new \Exception('يرجى تحديد العدد بشكل صحيح');

            }


            if (auth()->user()->balance < ($this->product->getPrice() * $this->count)) {
                $msg = 'لا تمتلك رصيد كافي ❌
يرجى التواصل مع الإدارة 🖥
💲لتعبئة الرصيد💲
رقم الدعم الفني📱
                +905388543380';
                throw new \Exception($msg);
                /*if(auth()->user()->email=='market5@gmail.com'){
    $msg='لا تمتلك رصيد كافي ❌
    يرجى التواصل مع الوكيل 🖥
    لتعبئة الرصيد💲

    @212627607678';
                }*/
            }
            $items = $this->product->items()->whereNull('bill_id')->limit($this->count)->get();


            if ($items->count() == 0) {
                throw new \Exception( 'المنتج غير متوفر حاليا');
            }


#####################################################
            $invoice = Invoice::create([
                'status' => BillStatusEnum::COMPLETE->value,
                'code' => uniqid('M', true),
                'total' => ($this->product->getPrice() * $this->count),
                'user_id' => auth()->id()
            ]);
            \DB::beginTransaction();
            try {

                foreach ($items as $item) {
                    $price=$this->product->getPrice();
                    $ratio=0;
                    if(auth()->user()->user!=null){
                        $ratio=($this->product->total_cost*auth()->user()->group->ratio_delegate);
                    }elseif(auth()->user()->affiliate_user!=null){
                        $ratio=($this->product->total_cost*getSettings('affiliate_ratio'));
                    }
                    $bill = Bill::create([
                        'id_bill'=>Str::random(),
                        'price' =>$price ,
                        'ratio' =>$ratio,
                        'status' => BillStatusEnum::COMPLETE->value,
                        'user_id' => auth()->id(),
                        'category_id' => $this->product->category_id,
                        'cost' => $this->product->total_cost,
                        'invoice_id' => $invoice->id,
                        'product_id' => $this->product->id,
                        'data_id' => $item->code,
                    ]);
                    $item->update([
                        'bill_id' => $bill->id,
                        'active' => 0,
                    ]);
                    auth()->user()->balances()->create([
                        'total' => auth()->user()->balance - $price,
                        'info' => 'شراء كود من منتج ' . $this->product->name,
                        'debit' => $price

                    ]);
                    if ((auth()->user()->user != null || auth()->user()->affiliate_user != null) && !$this->product->is_offer) {
                        Point::create([
                            'user_id'=>auth()->user()->user_id??auth()->user()->affiliate_id,
                            'credit' => $ratio,
                            'info' => 'رح من شراء '.$this->product->name.' الزبون : '.auth()->user()->name,
                        ]);
                    }
                }

                \DB::commit();
                $phone = ltrim(ltrim(getSettings('whats_activate'), '+'), '00');
                $msg = "السلام عليكم ورحمة الله \n " . $this->product->name . ' كود المنتج : ' . $item->code;
                $this->dispatchBrowserEvent('success', ['msg' => 'تم شراء المنتج بنجاح']);
               if($this->product->items->count()==0){
                   $this->product->update(['is_available'=>0]);
               }
                if (Str::contains($item, '--wa--')) {
                    $this->redirect('https://wa.me/' . $phone . '?text=' . $msg);
                } else {
                    $this->redirectRoute('invoices.index');
                }


            } catch (\Exception | \Error $e) {
                \DB::rollBack();
                $invoice->delete();
                $this->dispatchBrowserEvent('error', ['msg' => 'حدث خطأ غير متوقع يرجى المحاولة لاحقا' . $e->getMessage()]);
            }
        } catch (\Exception | \Error $exception) {
            $this->dispatchBrowserEvent('error', ['msg' => $exception->getMessage()]);
        }


    }
}
