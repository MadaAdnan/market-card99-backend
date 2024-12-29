<?php

namespace App\Http\Livewire\Dashboard\Presint;

use App\Models\Presint;
use Livewire\Component;
use Livewire\WithPagination;

class ListPresintComponent extends Component
{
    use WithPagination;

     protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.dashboard.presint.list-presint-component',[
            'presints'=>Presint::latest()->paginate(20),
        ]);
    }
      public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'prisent']);
    }

    public function confirmDelete($event){
        if($event['model']=='prisent'){
            try {
                $program=Presint::whereId($event['id'])->first();
                $program->delete();

                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
             $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }

    public function toggle($id){
       $present= Presint::find($id);
       $present->update([
           'status'=>!$present->status,
       ]);
    }
}
