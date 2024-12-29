<?php

namespace App\Http\Livewire\Site\Products;

use App\Enums\BillStatusEnum;
use App\Enums\CurrencyEnum;
use App\Enums\ProductTypeEnum;
use App\FromApi\Drd3;
use App\FromApi\EkoCard;
use App\FromApi\LifeCash;
use App\FromApi\SpeedCard;
use App\Models\Balance;
use App\Models\Bill;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;
use PhpParser\Node\Stmt\TryCatch;

class BuyProductComponent extends Component
{

    public Product $product;

    public $id_user;
    public $name;
    public $info;
    public $count = 1;
    public $loading = false;
    public $price = 0;
    public $amount = 0;
    public string $orderUuid = '';
    public string $orderUuid2 = '';

    public function mount(Product $product)
    {
        $this->product = $product;

        $this->orderUuid = 'test';
        if ($product->is_free == 1) {
            $this->amount = $product->min_amount;
            $this->price = $product->getPrice() / $this->product->amount * $product->min_amount;
        }
    }

    public function render()
    {
        return view('livewire.site.products.buy-product-component');
    }

    public function updatedAmount()
    {
        $this->price = ($this->product->getPrice() / $this->product->amount) * (!empty($this->amount) ? $this->amount : 0);
    }


    public function submit()
    {
        try {
            if (!$this->product->is_available || !$this->product->active || !$this->product->category->active) {
                throw new \Exception('المنتج غير متوفر حاليا');
            }
            if ($this->orderUuid == $this->orderUuid2) {
                throw new \Exception('يرجى تحديث الصفحة قبل الشراء مرة أخرى');
            } else {
                $this->orderUuid2 = $this->orderUuid;
            }

            if (!auth()->check()) {
                throw new \Exception('يرجى تسجيل الدخول قبل عملية الشراء');
            }

            ##############
            if (auth()->user()->email == 'market5@gmail.com') {
                $msg = 'لا تمتلك رصيد كافي ❌
يرجى التواصل مع الوكيل 🖥
لتعبئة الرصيد💲

@212627607678';
                throw new \Exception($msg);
            }//
            elseif (auth()->user()->email == 'market10@gmail.com') {
                $msg = 'لا تمتلك رصيد كافي ❌
يرجى التواصل مع الوكيل 🖥
لتعبئة الرصيد💲

@905356060166';
                throw new \Exception($msg);
            }


            ############


            if (empty($this->id_user)) {
                throw new \Exception('يرجى إضافة ID  الحساب');
            }
            $bill = new Bill();
            if ($this->product->is_free) {
                if ($this->amount < $this->product->min_amount) {
                    throw new \Exception('لا يمكن طلب كمية أقل من ' . $this->product->min_amount);
                }
                if ($this->amount > $this->product->max_amount) {
                    throw new \Exception('لا يمكن طلب كمية أكبر من ' . $this->product->max_amount);
                }
                $total_cost = ($this->product->total_cost / $this->product->amount) * $this->amount;
                $total_price = ($this->product->getPrice() / $this->product->amount) * $this->amount;
                $ratio = 0;
                $bill->amount = $this->amount;
            }//
            else {
                $total_cost = $this->product->total_cost;
                $total_price = $this->product->getPrice();
                $ratio = 0;
                $bill->amount = $this->product->count;

            }
            if (!$this->product->is_offer && auth()->user()->user != null) {
                $ratio = ($total_cost * auth()->user()->group->ratio_delegate);
                $bill->ratio = $ratio;
            }//
            elseif (!$this->product->is_offer && auth()->user()->affiliate_user != null) {
                $ratio = ($total_cost * getSettings('affiliate_ratio'));
                $bill->ratio = $ratio;
            }
            if (auth()->user()->balance < $total_price) {
                throw new \Exception('لا تملك رصيد كافي للشراء');

            }//
            $invoice = Invoice::create([
                'status' => BillStatusEnum::PENDING->value,
                'user_id' => auth()->id(),
                'code' => Str::random(6),
                'total' => $total_price,
            ]);
            $bill->id_bill = Str::uuid();
            $bill->price = $total_price;
            $bill->cost = $total_cost;
            $bill->user_id = auth()->id();
            $bill->status = BillStatusEnum::PENDING->value;
            $bill->category_id = $this->product->category_id;
            $bill->product_id = $this->product->id;
            $bill->customer_note = $this->info;
            $bill->invoice_id = $invoice->id;
            ########################
            if ($this->product->type == ProductTypeEnum::NEED_ID) {

                $bill->customer_id = $this->id_user;
                $bill->customer_name = $this->name;
            }//
            elseif ($this->product->type == ProductTypeEnum::NEED_ACCOUNT) {
                $bill->customer_username = $this->id_user;
                $bill->customer_password = $this->name;
            }
            #########################3
            Balance::create([
                'user_id' => auth()->id(),
                'debit' => $total_price,
                'credit' => 0,
                'info' => 'شراء منتج ' . $bill->amount . ' ' . $this->product->name,
                'total' => auth()->user()->balance - $total_price
            ]);


            if ($this->product->is_free && $this->product->is_active_api) {

                if ($this->product->api == 'life-cash') {
                    $service = new LifeCash(getSettingsModel());
                }//
                elseif ($this->product->api == 'speed-card') {
                    $service = new SpeedCard(getSettingsModel());
                } elseif ($this->product->api == 'drd3') {
                    $service = new Drd3(getSettingsModel());
                }//
                else {
                    $service = new EkoCard(getSettingsModel());
                }
                $bill = $service->buyFromApiFree($bill,);

            } //
            elseif (!$this->product->is_free && $this->product->is_active_api) {

                if ($this->product->api == 'life-cash') {
                    $service = new LifeCash(getSettingsModel());
                }//
                elseif ($this->product->api == 'speed-card') {
                    $service = new SpeedCard(getSettingsModel());
                }//
                elseif ($this->product->api == 'drd3') {
                    $service = new Drd3(getSettingsModel());
                }//
                else {
                    $service = new EkoCard(getSettingsModel());
                }
                $bill = $service->buyFromApiFixed($bill);
            }//
            try {
                $bill->save();
            } catch (\Exception | \Error $e) {
                info('FROM SAVE ' . $e->getMessage());
            }

            $this->redirectRoute('invoices.index');
        } //
        catch (\Exception | \Error $e) {
            $this->orderUuid2 = '';
            $this->dispatchBrowserEvent('error', ['msg' => $e->getMessage()]);
        }
    }

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        if ($this->count > 1) {
            $this->count--;
        }
    }
}
