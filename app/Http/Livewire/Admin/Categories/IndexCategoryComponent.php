<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCategoryComponent extends Component
{

    use WithPagination;

    public $search;
    public function render()
    {
        return view('livewire.admin.categories.index-category-component',[
            'categories'=>Category::where(function($query){
                $query->where('name','like','%'.$this->search.'%');
            })->orderBy('name')->paginate(10),

        ]);
    }

    public function toggle($id){
      $category=  Category::find($id);
      $category->update([
          'is_available'=>!$category->is_available,
      ]);
    }
}
