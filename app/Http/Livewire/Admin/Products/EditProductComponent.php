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

class EditProductComponent extends Component
{

    use WithFileUploads;

    public Product $product;
    public $img;
    public $type = ProductTypeEnum::DEFAULT;
    public $name;
    public $info;
    public $cost;
    public $count;
    public $code;
    public $category_id;
    public $is_discount = false;
    public $currency = CurrencyEnum::USD;
public $api;
    public $prices = [];
    public $active = 1;
    public $is_free = 0;
    public $amount = 0;
    public $min_amount = 0;
    public $max_amount = 0;
    public $is_active_api;
    public $is_offer = false;
public $codes_api;
    public function mount(Product $product)
    {
        $this->product = $product;

        $this->type = $this->product->type;
        $this->name = $this->product->name;
        $this->info = $this->product->info;
        $this->cost = $this->product->cost;
        $this->category_id = $this->product->category_id;
        $this->currency = $this->product->currency;
        $this->active = $this->product->active;
        $this->is_free = $this->product->is_free;
        $this->amount = $this->product->amount;
        $this->min_amount = $this->product->min_amount;
        $this->max_amount = $this->product->max_amount;
        $this->count = $this->product->count;
        $this->code = $this->product->code;
        $this->is_active_api = $this->product->is_active_api;
        $this->is_discount = $this->product->is_discount;
        $this->is_offer = $this->product->is_offer;
        $this->codes_api = $this->product->codes_api;
        $this->api = $this->product->api;
    }

    public function updatedIsFree()
    {
        if ($this->is_free == 0) {
            $this->amount = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.products.edit-product-component', [
            'categories' => Category::whereType(CategoryTypeEnum::DEFAULT)->get(),

        ]);
    }

    public function submit()
    {

        $this->validate([
            'category_id' => 'required|exists:categories,id',

            'name' => 'required',
            'img' => 'nullable|image',
            'cost' => 'required|numeric',
            'currency' => ['required']
        ]);

        $data = [

            'type' => $this->type,
            'name' => $this->name,
            'info' => $this->info,
            'cost' => $this->cost,
            'category_id' => $this->category_id,
            'currency' => $this->currency,
            'active' => $this->active,
            'is_free' => $this->is_free,
            'amount' => $this->amount,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'is_discount' => $this->is_discount,
            'is_offer' => $this->is_offer,
            'is_active_api' => $this->is_active_api,
            'codes_api' => $this->codes_api,
            'api' => $this->api
        ];
        if ($this->img) {
            $data['img'] = \Storage::disk('public')->put('products', $this->img);
            if ($this->product->img != null && \Storage::disk('public')->exists($this->product->img)) {
                \Storage::disk('public')->delete($this->product->img);
            }
        }
        if ($this->is_active_api) {
            $data['code'] = $this->code;
            $data['count'] = $this->count;
        }


        $this->product->update($data);


        $this->dispatchBrowserEvent('success', ['msg' => 'تم تعديل المنتج بنجاح']);
        $this->redirectRoute('dashboard.products.index', ['category_id' => $this->product->category_id]);
    }
}
