<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class OrderUserComponent extends Component
{
    use WithPagination;
    public User $user;
    public $search;
    public function render()
    {
        return view('livewire.admin.users.order-user-component',[
            'orders'=>Order::whereUserId($this->user->id)->where(function($query){
                $query->where('phone','like','%'.$this->search.'%');
                $query->orWhere('code','like','%'.$this->search.'%');
            })->latest()->paginate(10),
        ]);
    }
}
