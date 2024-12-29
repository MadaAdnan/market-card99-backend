<?php

namespace App\Http\Livewire\Admin\Programs;

use App\Models\Program;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProgramComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $img;
    public Program $program;

    public function mount(Program $program){
        $this->program=$program;
        $this->name=$this->program->name;
    }
    public function render()
    {
        return view('livewire.admin.programs.edit-program-component');
    }
    public function submit(){
        $this->validate([
            'name'=>'required|unique:programs,name,'.$this->program->id,
            'img'=>'nullable|image'
        ]);
        $data=[
            'name'=>$this->name,

        ];
        try {
            if($this->img!=null){
                $data['img']=\Storage::disk('public')->put('programs',$this->img);
                if($this->program->img!=null && \Storage::disk('public')->exists($this->program->img)){
                    \Storage::disk('public')->delete($this->program->img);
                }
            }
            $this->program->update($data);
            $this->dispatchBrowserEvent('success',['msg'=>'تم تعديل التطبيق بنجاح']);
        }catch (\Exception | \Error $e)
        {
            $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
        }

    }
}
