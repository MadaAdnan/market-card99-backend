<?php

namespace App\Http\Livewire\Dashboard\Ask;

use App\Models\Ask;
use Livewire\Component;
use Livewire\WithPagination;

class ListAskComponent extends Component
{
    use WithPagination;

     protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.dashboard.ask.list-ask-component',[
            'asks'=>Ask::paginate(20),
        ]);
    }

    public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'ask']);
    }

    public function confirmDelete($event){
        if($event['model']=='ask'){
            try {
                $program=Ask::whereId($event['id'])->first();
                $program->delete();

                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
             $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }
}
