<?php

namespace App\Http\Livewire\Admin\Countries;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCountryComponent extends Component
{
    use WithFileUploads;

    public $name;
    public $img;
    public function render()
    {
        return view('livewire.admin.countries.create-country-component');
    }

    public function submit(){
        $this->validate([
            'name'=>'required|unique:countries,name',
            'img'=>'required|image'
        ]);
        try {
            Country::create([
                'name'=>$this->name,
                'img'=>\Storage::disk('public')->put('countries',$this->img),
            ]);
            $this->reset();
            $this->img=null;
            $this->dispatchBrowserEvent('success',['msg'=>'تم إضافة الدولة بنجاح']);
        }catch (\Exception | \Error $e)
        {
            $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
        }

    }
}
