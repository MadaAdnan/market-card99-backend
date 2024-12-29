<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Group;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUserComponent extends Component
{

    public $name;
    public $email;
    public $username;
    public $password;
    public $phone;
    public $address;
    public $group_id;
    public $roles_id=[];
    public User $user;
    public $ratio=0;
    public $active=1;
    public $ratio_online=0;
    public $is_show=true;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->email = $this->user->email;
        $this->username = $this->user->username;
        $this->name = $this->user->name;
        $this->phone = $this->user->phone;
        $this->address = $this->user->address;
        $this->group_id = $this->user->group_id;
        $this->roles_id=$this->user->roles()->pluck('id')->toArray();
        $this->user_id=$this->user->user_id;
        $this->ratio=$this->user->ratio;
        $this->active=$this->user->active;
        $this->ratio_online=$this->user->ratio_online;
        $this->is_show=$this->user->is_show;
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user-component',  [
            'groups' => Group::all(),
            'roles'=>Role::all(),
            'users'=>User::whereHas('roles',function($query){
                $query->where('name','partner');
            })->get(),
        ]);
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $this->user->id,
            'email' => 'required|unique:users,email,' . $this->user->id,
            'password' => 'nullable|min:8',
            //'group_id' => 'required|exists:groups,id',
        ]);
        $data = [
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'group_id' => $this->group_id,
            'user_id'=>$this->user_id,
            'ratio'=>$this->ratio,
            'active'=>$this->active,
            'ratio_online'=>$this->ratio_online,
            'is_show'=>$this->is_show
        ];
        if ($this->password != null) {
            $data['password'] = bcrypt($this->password);
        }
        $this->user->update($data);
        $this->user->roles()->sync($this->roles_id);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم تعديل المستخدم بنجاح']);
    }
}
