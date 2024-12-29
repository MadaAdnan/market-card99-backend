<?php

namespace App\Http\Livewire\Admin\Groups;

use App\Models\Group;
use Livewire\Component;

class CreateGroupComponent extends Component
{

    public $name;
    public $price;
    public function render()
    {
        return view('livewire.admin.groups.create-group-component');
    }

    public function submit(){
        $this->validate([
            'name'=>'required|unique:groups,name',
            'price'=>'required|numeric',
        ]);
        Group::create([
            'name'=>$this->name,
            'price'=>$this->price,
        ]);
        $this->reset(['name','price']);
        $this->dispatchBrowserEvent('success',['msg'=>'تم إضافة الفئة بنجاح تأكد من تسعير المنتجات']);

    }
}
