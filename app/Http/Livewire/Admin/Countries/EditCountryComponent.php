<?php

namespace App\Http\Livewire\Admin\Countries;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCountryComponent extends Component
{
    use WithFileUploads;

    public $name;
    public $img;


    public function mount(Country $country){
        $this->country=$country;
        $this->name=$this->country->name;
    }
    public function render()
    {
        return view('livewire.admin.countries.edit-country-component');
    }

    public function submit(){

        $this->validate([
            'name'=>'required|unique:countries,name,'.$this->country->id,
            'img'=>'nullable|image'
        ]);
        try {
            $data=[
                'name'=>$this->name,

            ];
            if($this->img!=null){
                $data['img']=\Storage::disk('public')->put('countries',$this->img);
                if($this->country->img !=null && \Storage::disk('public')->exists($this->country->img)){
                    \Storage::disk('public')->delete($this->country->img);
                }
            }
            $this->country->update($data);
            //$this->reset();
            //$this->img=null;
            $this->dispatchBrowserEvent('success',['msg'=>'تم إضافة الدولة بنجاح']);
        }catch (\Exception | \Error $e)
        {
            $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
        }

    }
}
