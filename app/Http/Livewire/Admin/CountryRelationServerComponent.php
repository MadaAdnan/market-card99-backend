<?php

namespace App\Http\Livewire\Admin;

use App\Models\Country;
use App\Models\Server;
use Livewire\Component;

class CountryRelationServerComponent extends Component
{



      public Server $server;
    public $country_ids = [];
    public $country_code = [];

    public function mount(Server $server)
    {
        $this->server = $server;

        foreach ($server->countries as $country) {
            $this->country_ids[] = $country->id;
            $this->country_code[$country->id] = $country->pivot->code;

        }
    }

    public function render()
    {
        return view('livewire.admin.country-relation-server-component',[
            'countries'=>Country::all(),
        ]);
    }

     public function submit()
    {
        $this->server->countries()->sync([]);
        foreach ($this->country_ids as $id) {
            $this->server->countries()->attach($id, [
                'code' => $this->country_code[$id],
            ]);
        }
        $this->dispatchBrowserEvent('success', ['msg' => 'تم ربط الدول بنجاح']);
    }

}
