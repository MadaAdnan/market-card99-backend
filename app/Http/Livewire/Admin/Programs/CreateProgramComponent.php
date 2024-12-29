<?php

namespace App\Http\Livewire\Admin\Programs;

use App\Models\Program;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CreateProgramComponent extends Component
{
    use WithFileUploads;

    public $name;
    public $img;

    public function render()
    {
        return view('livewire.admin.programs.create-program-component');
    }
    public function submit(){
        $this->validate([
            'name'=>'required|unique:programs,name',
            'img'=>'required|image'
        ]);
        try {
            Program::create([
                'name'=>$this->name,
                'img'=>\Storage::disk('public')->put('programs',$this->img),
            ]);
            $this->reset();
            $this->img=null;
            $this->dispatchBrowserEvent('success',['msg'=>'تم إضافة التطبيق بنجاح']);
        }catch (\Exception | \Error $e)
        {
            $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
        }

    }

}
