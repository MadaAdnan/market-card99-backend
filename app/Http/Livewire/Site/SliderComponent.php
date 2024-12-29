<?php

namespace App\Http\Livewire\Site;

use App\Models\Slider;
use Livewire\Component;

class SliderComponent extends Component
{
    public function render()
    {
        return view('livewire.site.slider-component',[
            'sliders'=>Slider::all(),
        ]);
    }
}
