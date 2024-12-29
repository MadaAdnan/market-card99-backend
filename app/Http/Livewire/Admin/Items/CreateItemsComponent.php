<?php

namespace App\Http\Livewire\Admin\Items;

use App\Imports\PhoneImport;
use App\Models\Item;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CreateItemsComponent extends Component
{
    use WithFileUploads;

    public $type = 'ex';
    public $file;
    public $code1;
    public $code2;
    public $code3;
    public $code4;
    public $code5;
    public $code6;
    public $code7;
    public $code8;
    public $code9;
    public $code10;
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.admin.items.create-items-component');
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
        Excel::import(new PhoneImport($this->product), $this->file);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم رفع الأكواد']);
    }

    public function submit()
    {
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($this->{'code' . $i})) {
                Item::create([
                    'code' => $this->{'code' . $i},
                    'active' => 1,
                    'product_id'=>$this->product->id
                ]);
            }
        }
        $this->dispatchBrowserEvent('success', ['msg' => 'تم رفع الأكواد']);
        $this->reset(['type','code1','code2','code3','code4','code5','code6','code7','code8','code9','code10']);
    }
}
