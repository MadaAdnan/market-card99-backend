<?php

namespace App\Http\Livewire\Admin\Items;

use App\Models\Item;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class IndexItemsComponent extends Component
{
    use WithPagination;
    public Product $product;
    protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.admin.items.index-items-component',[
            'items'=>Item::whereProductId($this->product->id)->whereNull('bill_id')->paginate(20),
        ]);
    }

    public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'items']);
    }

    public function confirmDelete($event){
        if($event['model']=='items'){
            try {
                $item=Item::whereId($event['id'])->first();
                $item->delete();

                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
                $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }


}
