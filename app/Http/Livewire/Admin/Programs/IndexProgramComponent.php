<?php

namespace App\Http\Livewire\Admin\Programs;

use App\Models\Program;
use Livewire\Component;
use Livewire\WithPagination;

class IndexProgramComponent extends Component
{
    use WithPagination;
    public $search;

     protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.admin.programs.index-program-component',[
            'programs'=>Program::where('name','like','%'.$this->search.'%')->orderBy('name')->paginate(20)
        ]);
    }
     public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'countries']);
    }

    public function confirmDelete($event){
        if($event['model']=='countries'){
            try {
                $program=Program::whereId($event['id'])->first();
                $program->delete();
                if($program->img !=null && \Storage::disk('public')->exists($program->img)){
                    \Storage::disk('public')->delete($program->img);
                }
                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
             $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }
}
