<?php

namespace App\Http\Livewire\Dashboard\Ask;

use App\Models\Ask;
use Livewire\Component;

class EditAskComponent extends Component
{

     public $ask;
     public $title;
    public $is_active='active';

    public function mount(Ask $ask){
        $this->ask=$ask;
        $this->title=$ask->ask;
        $this->is_active=$ask->is_active;
    }
    public function render()
    {
        return view('livewire.dashboard.ask.edit-ask-component');
    }
     public function submit(){
        $this->validate([
            'title'=>'required',
        ]);
        $this->ask->update([
            'ask'=>$this->title,
            'is_active'=>$this->is_active,
        ]);

        $this->dispatchBrowserEvent('success',['msg'=>'تم تعديل السؤال بنجاح']);
    }
}
