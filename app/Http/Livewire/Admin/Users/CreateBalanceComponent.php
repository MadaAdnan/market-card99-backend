<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Balance;
use App\Models\User;
use http\Exception\RuntimeException;
use Livewire\Component;
use function PHPUnit\Framework\throwException;

class CreateBalanceComponent extends Component
{
    public User $user;
    public $type_proccess = 'push';
    public $amount;
    public $info;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.admin.users.create-balance-component');
    }

    public function submit()
    {

        $this->validate([
            'amount' => 'required|numeric',

        ]);
        $data = [
            'user_id' => $this->user->id,
            'credit' => $this->type_proccess == 'push' ? $this->amount : 0,
            'debit' => $this->type_proccess != 'push' ? $this->amount : 0,
            'info' => $this->info . ' عن طريق المدير',
        ];
        if ($this->type_proccess == 'push') {

            $data['total'] = auth()->user()->balance + $this->amount;
            if($this->amount<=0){
                $this->dispatchBrowserEvent('error', ['msg' => 'يجب إدخال قيمة صالحة']);
                return;
            }
        } else {
            $data['total'] = auth()->user()->balance - $this->amount;
        }

        try {
            if (!auth()->user()->hasRole('super_admin')) {

                throw_if(auth()->user()->balance < $this->amount, new \Exception('test'));
                $data['info']= $this->info . ' عن طريق الوكيل';
                Balance::create($data);
                auth()->user()->balances()->create([
                    'debit' => $this->amount,
                    'info' => 'شحن للزبون '.$this->user->name,
                    'total' => auth()->user()->balance-$this->amount,
                ]);
            }else{
                Balance::create($data);
            }


            $this->reset(['amount', 'type_proccess', 'info']);
            $this->dispatchBrowserEvent('success', ['msg' => 'تمت العملية بنجاح']);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('error', ['msg' => 'لا تملك رصيد كافي للشحن']);
        }

    }
}
