<?php

namespace App\Http\Livewire\Admin\Products;

use App\Enums\CategoryTypeEnum;
use App\Enums\CurrencyEnum;
use App\Enums\ProductTypeEnum;
use App\Models\Category;
use App\Models\Group;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProductComponent extends Component
{

    use WithFileUploads;

    public $img;
    public $type = ProductTypeEnum::DEFAULT;
    public $name;
    public $info;
    public $api;
    public $cost;
    public $category_id;
    public $is_discount=false;
    public $is_offer=false;

    public $currency='usd';
    public $is_free=0;
    public $amount=0;
    public $min_amount=0;
    public $max_amount=0;
    public $count=0;
    public $code=0;
    public $is_active_api=0;
public $codes_api;
    public $prices = [];
public $active=1;

    public function mount(){
        $this->prices=Group::pluck('id','id')->toArray();
    }
    public function render()
    {

        return view('livewire.admin.products.create-product-component', [
            'categories' => Category::whereType(CategoryTypeEnum::DEFAULT->value)->get(),

        ]);
    }


    public function updatedIsFree(){
        if($this->is_free==0){
            $this->amount=0;
        }
    }
    public function submit()
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',

            'name' => 'required',
            'img' => 'required|image',
            'cost' => 'required|numeric',
            'currency'=>['required',Rule::in(['tr','usd'])]
        ]);

        $data = [

            'type' => $this->type,
            'name' => $this->name,
            'info' => $this->info,
            'cost' => $this->cost,
            'category_id' => $this->category_id,
            'currency'=>$this->currency,
            'active'=>$this->active,
            'is_free'=>$this->is_free,
            'amount'=>$this->amount,
            'min_amount'=>$this->min_amount,
            'max_amount'=>$this->max_amount,
            'code'=>$this->code,
            'count'=>$this->count,
            'is_active_api'=>$this->is_active_api,
            'is_discount'=>$this->is_discount,
            'is_offer'=>$this->is_offer,
            'codes_api'=>$this->codes_api,
            'api'=>$this->api
        ];
        if ($this->img) {
            $data['img'] = \Storage::disk('public')->put('products', $this->img);
        }
        $product = Product::create($data);


        $this->reset();
        $this->dispatchBrowserEvent('success', ['msg' => 'تم إضافة المنتج بنجاح']);
    }
}
