<?php

namespace App\Http\Livewire\Admin\Servers;

use App\Models\Server;
use Livewire\Component;
use Livewire\WithPagination;

class IndexServerComponent extends Component
{

    use WithPagination;

    public $search;

     protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.admin.servers.index-server-component',[
            'servers'=>Server::where('name','like','%'.$this->search.'%')->orderBy('name')->paginate(),
        ]);
    }

     public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'server']);
    }

    public function confirmDelete($event){
        if($event['model']=='server'){
            try {
                $server=Server::whereId($event['id'])->first();
                $server->delete();
                if($server->img !=null && \Storage::disk('public')->exists($server->img)){
                    \Storage::disk('public')->delete($server->img);
                }
                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
             $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }
}
