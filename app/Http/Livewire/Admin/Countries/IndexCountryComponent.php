<?php

namespace App\Http\Livewire\Admin\Countries;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCountryComponent extends Component
{
    use WithPagination;
    public $search;
    protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.admin.countries.index-country-component',[
            'countries'=>Country::where('name','like','%'.$this->search.'%')->orderBy('name')->paginate(20),
        ]);
    }

    public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'countries']);
    }

    public function confirmDelete($event){
        if($event['model']=='countries'){
            try {
                $country=Country::whereId($event['id'])->first();
                $country->delete();
                if($country->img !=null && \Storage::disk('public')->exists($country->img)){
                    \Storage::disk('public')->delete($country->img);
                }
                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
             $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }
}
