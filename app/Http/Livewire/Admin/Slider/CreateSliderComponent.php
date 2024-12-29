<?php

namespace App\Http\Livewire\Admin\Slider;

use App\Models\Slider;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateSliderComponent extends Component
{
    use WithFileUploads;
    public $img;

    public function render()
    {
        return view('livewire.admin.slider.create-slider-component');
    }

    public function submit(){
        Slider::create([
            'img'=>\Storage::disk('public')->put('sliders',$this->img)
        ]);
        $this->dispatchBrowserEvent('success',['msg'=>'ok']);
        $this->reset();
    }
}
