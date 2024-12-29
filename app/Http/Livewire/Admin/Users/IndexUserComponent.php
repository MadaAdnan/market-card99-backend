<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class IndexUserComponent extends Component
{

    use WithPagination;

    public $search;
    public $type;
    protected $listeners=['confirmDelete'];
    public function render()
    {
        return view('livewire.admin.users.index-user-component',[
            'roles'=>Role::all(),
            'users'=>User::when(!auth()->user()->hasRole('super_admin'),function($query){
                $query->where('user_id',auth()->id());
            })->when($this->type!=null,function ($query){
                $query->whereHas('roles',function($query){
                    $query->where('name',$this->type);
                });
            })->where(function($query){
                $query->where('name','like','%'.$this->search.'%');
                $query->orWhere('phone','like','%'.$this->search.'%');
                $query->orWhere('email','like','%'.$this->search.'%');
            })->with('balances')->paginate(20),
        ]);
    }
     public function delete($id){
        $this->dispatchBrowserEvent('deleteData',['id'=>$id,'model'=>'users']);
    }

    public function confirmDelete($event){
        if($event['model']=='users'){
            try {
                $user=User::whereId($event['id'])->first();
                $user->update(['user_id'=>null]);
                $user->balances()->delete();
                $user->delete();
                if($user->img !=null && \Storage::disk('public')->exists($user->img)){
                    \Storage::disk('public')->delete($user->img);
                }
                $this->dispatchBrowserEvent('success',['msg'=>'تم الحذف بنجاح']);
            }catch (\Exception | \Error $e){
             $this->dispatchBrowserEvent('error',['msg'=>$e->getMessage()]);
            }

        }
    }
}
