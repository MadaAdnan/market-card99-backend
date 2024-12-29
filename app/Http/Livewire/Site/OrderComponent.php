<?php

namespace App\Http\Livewire\Site;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderComponent extends Component
{
    use WithPagination;
    public $search;
    protected $listeners = ['buyOrder','sendCode'=>'$refresh'];

    public function render()
    {
        return view('livewire.site.order-component', [
            'orders' => Order::whereUserId(auth()->id())->where(function ($query) {
                $query->where('phone', 'like', '%' . $this->search . '%');
                $query->orWhere('code', 'like', '%' . $this->search . '%');
            })->with(['program', 'server', 'country'])->latest()->paginate(15),
        ]);
    }

    public function buyOrder()
    {

    }
}
