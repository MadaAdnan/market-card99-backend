<?php

namespace App\Http\Livewire\Admin\Orders;

use App\InterFaces\ServerInterface;
use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class IndexOrderComponent extends Component
{
    use WithPagination;
    public User|null $user;
    public $search;
    public function mount(User $user=null){
        $this->user=$user;
    }
    public function render()
    {
        return view('livewire.admin.orders.index-order-component',[
            'orders'=>Order::when($this->user?->id!=null,function ($query){
                $query->where('user_id',$this->user->id);
            })->where(function($query){
                $query->where('phone','like','%'.$this->search.'%');
                $query->orWhere('code','like','%'.$this->search.'%');
            })->with(['program','server','country','user'])->latest()->paginate(20),
        ]);
    }

    public function cancel(Order $order){
       $server= $order->server;
       /**
        * @var ServerInterface $lib
        * */
      $lib=new $server->code;
      try{
          $lib->cancelOrder($order);
      }catch (\Exception $exception){
          $this->dispatchBrowserEvent('error',$exception->getMessage());
      }

    }
}
