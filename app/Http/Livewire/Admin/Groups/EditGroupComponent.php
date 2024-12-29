<?php

namespace App\Http\Livewire\Admin\Groups;

use App\Models\Group;
use Livewire\Component;

class EditGroupComponent extends Component
{

    public $name;
    public $price;
    public Group $group;

    public function mount(Group $group)
    {
        $this->group = $group;
        $this->name = $this->group->name;
        $this->price = $this->group->price;
    }

    public function render()
    {
        return view('livewire.admin.groups.edit-group-component');
    }

    public function submit(){
        $this->validate([
            'name'=>'required|unique:groups,name,'.$this->group->id,
            'price'=>'required|numeric',
        ]);
        $this->group->update([
            'name'=>$this->name,
            'price'=>$this->price
        ]);

        $this->dispatchBrowserEvent('success',['msg'=>'تم تعديل الفئة بنجاح ']);

    }
}
