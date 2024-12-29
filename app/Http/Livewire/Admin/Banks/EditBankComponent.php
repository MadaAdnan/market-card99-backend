<?php

namespace App\Http\Livewire\Admin\Banks;

use App\Models\Bank;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditBankComponent extends Component
{
    use  WithFileUploads;
    public Bank $bank;
    public $name;
    public $info;
    public $iban;
    public $img;
    public $is_active = true;

    public function mount(Bank $bank){
        $this->bank=$bank;
        $this->name=$bank->name;
        $this->info=$bank->info;
        $this->iban=$bank->iban;
        $this->is_active=$bank->is_active;
    }
    public function render()
    {
        return view('livewire.admin.banks.edit-bank-component');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
        ]);
$data=[
    'name' => $this->name,
    'info' => $this->info,
    'iban' => $this->iban,
    'is_active' => $this->is_active
];
if($this->img!=null){
    $data['img']=\Storage::disk('public')->put('banks',$this->img);
    if($this->bank->img && \Storage::disk('public')->exists($this->bank->img)){
        try{
            \Storage::disk('public')->delete($this->bank->img);
        }catch (\Exception $e){

        }

    }
}
        $this->bank->update($data);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم تعديل طريقة الدفع بنجاح']);

    }
}
