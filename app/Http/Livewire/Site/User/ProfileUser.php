<?php

namespace App\Http\Livewire\Site\User;

use Livewire\Component;

class ProfileUser extends Component
{
    public $email;
    public $password;
    public $phone;

    public function mount(){
        $this->email=auth()->user()->email;
        $this->phone=auth()->user()->phone;
    }

    public function render()
    {
        return view('livewire.site.user.profile-user');
    }

    public function submit(){
        $this->validate([
            'email'=>'required|unique:users,email,'.auth()->id(),
            'password'=>'nullable|min:8',
            'phone'=>'required',
        ]);
        $data=[
            //'email'=>$this->email,

            'phone'=>$this->phone

        ];
        if(trim($this->password)){
            $data['password']=bcrypt($this->password);
        }
        if(auth()->user()->email!='market@gmail.com' &&
            auth()->user()->email!='market2@gmail.com'
            && auth()->user()->email!='market5@gmail.com'
            && auth()->user()->email!='market10@gmail.com' ){
            auth()->user()->update($data);
        }

        $this->dispatchBrowserEvent('success',['msg'=>'تم تعديل الملف الشخصي بنجاح']);
    }
}
