<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class IndexProductComponent extends Component
{
    use WithPagination;
    public $search;
    public $category_id;

    public function render()
    {
        return view('livewire.admin.products.index-product-component',[
            'products'=>Product::where(function($query){
                $query->where('name','like','%'.$this->search.'%');
            })->when($this->category_id,function ($query){
                $query->where('category_id',$this->category_id);
            })->orderBy('category_id')->with('items')->paginate(25),
            'categories'=>Category::orderBy('sort')->get(),
        ]);
    }

    public function toggle($id){
        $product=Product::find($id);
        //dd($product);
        $product->update([
            'is_available'=>!$product->is_available
        ]);
    }
}
