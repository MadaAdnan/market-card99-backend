<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class SortableProductComponent extends Component
{
    public Category $category;

    public function mount(Category $category){
        $this->category=$category;
    }
    public function render()
    {
        return view('livewire.admin.products.sortable-product-component',[
            'products'=>Product::whereCategoryId($this->category->id)->orderBy('sort')->get(),
        ]);
    }

    public function updateTaskOrder($list){
        foreach ($list as $item){
            Product::whereId($item['value'])->update(['sort'=>$item['order']]);
        }
    }
}
