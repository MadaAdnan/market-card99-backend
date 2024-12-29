<?php

namespace App\Http\Livewire\Admin\Banks;

use App\Models\Bank;
use Livewire\Component;
use Livewire\WithPagination;

class IndexBankComponent extends Component
{
    use WithPagination;
    protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.admin.banks.index-bank-component',[
            'banks'=>Bank::latest()->paginate(15)
        ]);
    }


    public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'bank']);
    }

    public function confirmDelete($event){
        if($event['model']=='bank'){
            try {
                Bank::whereId($event['id'])->first();

                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
                $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }
}
