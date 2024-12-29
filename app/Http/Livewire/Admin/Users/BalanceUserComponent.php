<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Balance;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class BalanceUserComponent extends Component
{
    use WithPagination;
    public User $user;
    public function render()
    {
        return view('livewire.admin.users.balance-user-component',[
            'balances'=>Balance::whereUserId($this->user->id)->latest()->paginate(10),
        ]);
    }
}
