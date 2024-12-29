<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Group;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUserComponent extends Component
{

    public $name;
    public $email;
    public $username;
    public $password;
    public $phone;
    public $address;
    public $group_id;
    public $roles_id=[];
    public $user_id;
    public $ratio=0;
    public $active=1;
    public $ratio_online=0;
    public $is_show=true;



    public function render()
    {
        return view('livewire.admin.users.create-user-component',[
            'groups'=>Group::all(),
            'roles'=>Role::all(),
            'users'=>User::whereHas('roles',function($query){
                $query->where('name','partner');
            })->get(),
        ]);
    }

    public function submit(){

        $this->validate([
            'name'=>'required',
            'username'=>'required|unique:users,username',
            'email'=>'required|unique:users,email',
            'password'=>'required|min:8',
        ]);
try{
    $user=User::create([
        'username'=>$this->username,
        'name'=>$this->name,
        'email'=>$this->email,
        'address'=>$this->address,
        'phone'=>$this->phone,
        'group_id'=>!auth()->user()->hasRole('super_admin') ?auth()->user()->group_id:$this->group_id,
        'password'=>bcrypt($this->password),
        'user_id'=>!auth()->user()->hasRole('super_admin') ?auth()->user()->id:null,
        'ratio'=>(float)$this->ratio,
        'active'=>(bool)$this->active,
        'ratio_online'=>$this->ratio_online,
        'is_show'=>$this->is_show
    ]);
    $user->roles()->sync($this->roles_id);
    $this->reset();
    $this->dispatchBrowserEvent('success',['msg'=>'تم إضافة المستخدم بنجاح']);
}catch (\Exception $e){
    $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
}


    }
}
