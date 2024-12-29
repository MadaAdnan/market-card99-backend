<?php

namespace App\Http\Livewire\Admin\Slider;

use App\Models\Slider;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSliderComponent extends Component
{

    use WithFileUploads;
    public $img;
    public Slider $slider;


    public function mount(Slider $slider){
        $this->slider=$slider;
    }
    public function render()
    {
        return view('livewire.admin.slider.edit-slider-component');
    }
    public function submit(){
       $this->slider->update([
            'img'=>\Storage::disk('public')->put('sliders',$this->img)
        ]);
        $this->dispatchBrowserEvent('success',['msg'=>'ok']);

    }
}
