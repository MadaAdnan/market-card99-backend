<?php

namespace App\Http\Livewire\Dashboard\Ask;

use App\Models\Ask;
use Livewire\Component;

class CreateAskComponent extends Component
{

    public $title;
    public $is_active='active';
    public function render()
    {
        return view('livewire.dashboard.ask.create-ask-component');
    }

    public function submit(){
        $this->validate([
            'title'=>'required',
        ]);
        Ask::create([
            'ask'=>$this->title,
            'is_active'=>$this->is_active,
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('success',['msg'=>'تم إضافة السؤال بنجاح']);
    }
}
