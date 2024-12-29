<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategoryComponent extends Component
{
use WithFileUploads;
    public $name;
    public $img;
    public $info;
    public $active=1;
    public $type= CategoryTypeEnum::DEFAULT;
    public $main_category;
    public function render()
    {
        return view('livewire.admin.categories.create-category-component',[
            'categories'=>Category::whereNull('category_id')->get(),
        ]);
    }

    public function submit(){
        $data=[
            'name'=>$this->name,

            'info'=>$this->info,
            'type'=>$this->type,
            'active'=>$this->active,
            'category_id'=>$this->main_category,
        ];
        if($this->img){
            $data['img']=\Storage::disk('public')->put('category',$this->img);
        }
        Category::create($data);
        $this->reset();
        $this->dispatchBrowserEvent('success',['msg'=>'تم إضافة القسم بنجاح']);
    }
}
