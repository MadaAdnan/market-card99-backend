<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Bill;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class BillUserComponent extends Component
{
    use WithPagination;

    public $search;
    public User $user;

    public function render()
    {
        return view('livewire.admin.users.bill-user-component', [
            'bills' => Bill::whereUserId($this->user->id)->where(function($query){
                $query->where('customer_id','like','%'.$this->search.'%');
                $query->orWhere('customer_name','like','%'.$this->search.'%');
                $query->orWhere('customer_username','like','%'.$this->search.'%');
            })->latest()->paginate(10),
        ]);
    }
}
