<?php

namespace App\Http\Livewire\Site;

use App\Models\Bill;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class ListBillsComponent extends Component
{
    use WithPagination;

    public $search;
    public function render()
    {
        return view('livewire.site.list-bills-component',[
            'bills'=>Bill::where('user_id',auth()->id())->where(function($query){
                    $query->where('customer_name','like','%'.$this->search.'%');
                    $query->orWhere('customer_id','like','%'.$this->search.'%');
                    $query->orWhere('customer_username','like','%'.$this->search.'%');
                    $query->orWhere('data_id','like','%'.$this->search.'%');
                    $query->orWhere('data_name','like','%'.$this->search.'%');
                    $query->orWhere('data_username','like','%'.$this->search.'%');

            })->latest()->paginate(20)
        ]);
    }
}
