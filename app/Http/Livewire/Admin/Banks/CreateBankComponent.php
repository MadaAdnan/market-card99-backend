<?php

namespace App\Http\Livewire\Admin\Banks;

use App\Models\Bank;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateBankComponent extends Component
{
    use WithFileUploads;

    public $name;
    public $info;
    public $img;
    public $iban;
    public $is_active = true;

    public function render()
    {
        return view('livewire.admin.banks.create-bank-component');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
        ]);
        $data = [
            'name' => $this->name,
            'info' => $this->info,
            'iban' => $this->iban,
            'is_active' => $this->is_active,

        ];
        if ($this->img) {
            $data['img'] = \Storage::disk('public')->put('banks', $this->img);
        }
        Bank::create($data);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم إضافة طريقة الدفع بنجاح']);
        $this->reset();
    }
}
