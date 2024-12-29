<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCategoryComponent extends Component
{

    use WithFileUploads;
    public Category $category;
    public $name;
    public $img;
    public $info;
    public $active=1;
    public $type= CategoryTypeEnum::DEFAULT;
    public  $main_category;

    public function mount(Category $category){
        $this->category=$category;
        $this->name=$this->category->name;
        $this->info=$this->category->info;
        $this->type=$this->category->type;
        $this->active=$this->category->active;
        $this->main_category=$category->category_id;

    }
    public function render()
    {
        return view('livewire.admin.categories.edit-category-component',[
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
            if($this->category->img!=null && \Storage::disk('public')->exists($this->category->img)){
                \Storage::disk('public')->delete($this->category->img);
            }
        }
        $this->category->update($data);

        $this->dispatchBrowserEvent('success',['msg'=>'تم تعديل القسم بنجاح']);
    }
}
