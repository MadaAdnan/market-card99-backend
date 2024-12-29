<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;

class SortableCategoryComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.categories.sortable-category-component',[
            'cats'=>Category::orderBy('sort')->get(),
        ]);
    }
    public function updateTaskOrder($list){
       foreach ($list as $item){
           Category::whereId($item['value'])->update(['sort'=>$item['order']]);
       }
    }
}
