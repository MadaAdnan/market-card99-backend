<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSettingComponent extends Component
{
    use WithFileUploads;

    public Setting $setting;
    public $name;
    public $email;
    public $phone;
    public $usd_price;
    public $api_sim90;
    public $news;
    public $img;
    public $is_active_sim90;
    public $win_sim90_ratio;
    public $discount_online;
    public $discount_delegate_online;
    public $info_present;
    public $whats_activate;
    public $fixed_ratio;
    public $apis;


    public function mount(Setting $setting)
    {
        $this->setting = $setting;
        $this->name = $this->setting->name;
        $this->email = $this->setting->email;
        $this->phone = $this->setting->phone;
        $this->usd_price = $this->setting->usd_price;
        $this->api_sim90 = $this->setting->api_sim90;
        $this->news = $this->setting->news;
        $this->is_active_sim90 = $this->setting->is_active_sim90;
        $this->win_sim90_ratio = $this->setting->win_sim90_ratio;
        $this->discount_online = $this->setting->discount_online;
        $this->discount_delegate_online = $this->setting->discount_delegate_online;
        $this->info_present = $this->setting->info_present;
        $this->whats_activate = $this->setting->whats_activate;
        $this->fixed_ratio = $this->setting->fixed_ratio;
        $this->apis=$this->setting->apis;

    }

    public function render()
    {
        return view('livewire.admin.setting.edit-setting-component');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'usd_price' => 'required|numeric',
            'phone' => 'required',
            'discount_online'=>'required|gt:0',
            'discount_delegate_online'=>'required|gt:0',
        ]);
        $data = [
            'email' => $this->email,
            'phone' => $this->phone,
            'name' => $this->name,
            'usd_price' => $this->usd_price,
            'api_sim90' => $this->api_sim90,
            'news' => $this->news,
            'is_active_sim90'=>$this->is_active_sim90,
            'win_sim90_ratio'=>$this->win_sim90_ratio,
            'discount_online'=>$this->discount_online,
            'discount_delegate_online'=>$this->discount_delegate_online,
            'info_present'=>$this->info_present,
            'whats_activate'=>$this->whats_activate,
            'fixed_ratio'=>$this->fixed_ratio,
            'apis'=>$this->apis
        ];
        if ($this->img) {
            $data['img'] = \Storage::disk('public')->put('settings', $this->img);
            if ($this->setting->img != null && \Storage::disk('public')->exists($this->setting->img)) {
                \Storage::disk('public')->delete($this->setting->img);
            }
        }
        $this->setting->update($data);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم حفظ التعديلات']);
    }
}
