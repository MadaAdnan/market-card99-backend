<?php

namespace App\Http\Livewire\Admin\Notifications;


use App\Jobs\SendMailJob;
use App\Jobs\SendNotificationJob;
use App\Models\User;
use App\Notifications\SendNotificationDB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateNotificationComponent extends Component
{
    use WithFileUploads;
    public $title;
    public $msg;
    public $user_id;
    public $img;

    public function render()
    {
        return view('livewire.admin.notifications.create-notification-component');
    }

    public function submit()
    {
        $this->validate([
            'title' => 'required',
            'msg' => 'required'
        ]);

        $data=['title'=>$this->title,'body'=>$this->msg,'route'=>route('home.index'),'admin'=>'1'];
        if($this->img){
            $data['img']=Storage::disk('public')->put('images',$this->img);
        }
        if($this->user_id){
            $user= User::find($this->user_id);
            if($user){

                $user->notify(new SendNotificationDB($data));
                $job=new SendNotificationJob($user,$data);
                dispatch($job);
            }
        }else{
          $users=User::all();
            foreach ($users as $user) {
                $user->notify(new SendNotificationDB($data));

          }
            $job=new SendNotificationJob($users,$data);
            dispatch($job);
        }
        $this->dispatchBrowserEvent('success',['msg'=>'تم إرسال الإشعار بنجاح']);
    }
}
