<?php

namespace App\Http\Livewire\Admin\Slider;

use App\Models\Slider;
use Livewire\Component;

class IndexSliderComponent extends Component
{

    protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.admin.slider.index-slider-component',[
            'sliders'=>Slider::latest()->paginate(15),
        ]);
    }

    public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'sliders']);
    }

    public function confirmDelete($event){
        if($event['model']=='sliders'){
            try {
                $slider=Slider::whereId($event['id'])->first();
                $slider->delete();
                if($slider->img !=null && \Storage::disk('public')->exists($slider->img)){
                    \Storage::disk('public')->delete($slider->img);
                }
                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
                $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }
}
