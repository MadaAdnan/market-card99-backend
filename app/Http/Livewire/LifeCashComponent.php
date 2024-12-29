<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class LifeCashComponent extends Component
{
    public $apps = [];
    public $data = [];
    public $search = '';

    public function render()
    {
        return view('livewire.life-cash-component');
    }

    public function getData($type)
    {
        $this->apps = [];
        $this->data = [];

        try {$setting = Setting::first();
            $url = 'https://api.life-cash.com/client/api/products';
            $token = $setting->apis['life'];

            switch ($type) {
                case 'life-cash':
                    $url = 'https://api.life-cash.com/client/api/products';
                    $token = $setting->apis['life'];

                    break;
                case 'speed-card':
                    $url = 'https://api.speedcard.vip/client/api/products';
                    $token = $setting->apis['speed_card'];

                    break;
                case 'eko':
                    $url = 'https://api.ekostore.co/client/api/products';
                    $token = $setting->apis['eko'];

                    break;
            }

            $response = Http::withHeaders([
                'api-token' => $token
            ])->get($url);
            // dd($response->json());
            if ($response->successful()) {
                $this->apps = $response->json();
                $this->data = $response->json();
                //dd($this->apps);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('error', ['msg' => 'لم يتمكن من جلب البيانات']);
        }
    }

    public function updatedSearch()
    {
        if (count($this->apps) > 0 && $this->search != '') {
            $this->data = collect($this->apps)->filter(function ($el) {
                return \Str::contains($el['name'], $this->search);
            });
            //dd($this->data);
        } else {
            $this->data = $this->apps;
        }
    }
}
