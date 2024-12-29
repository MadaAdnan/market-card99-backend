<?php

namespace App\Http\Livewire\Site;

use App\Models\Country;
use App\Models\Server;
use Livewire\Component;

class ServerCountryComponent extends Component
{
    public Server|null $server;
    public $server_id;
    public $country_id;
    public $country;
    public $countries = [];

    public $search;

    public function render()
    {

        return view('livewire.site.server-country-component', [
            'servers' => Server::active()->orderBy('sort')->get(),
        ]);
    }

    public function changeServerId($id)
    {
        $this->server_id = $id;
        $this->updatedServerId();
    }


    public function updatedServerId()
    {
        if ($this->server_id != null) {
            $this->server = Server::find($this->server_id);
            $this->countries = $this->server->countries;
            $this->country = null;
            $this->emit('selectServer', ['server_id' => $this->server->id]);
        }

    }

    public function updatedSearch()
    {
        if ($this->server_id != null)
            $this->countries = $this->server->countries()->where('name', 'like', '%' . $this->search . '%')->get();
    }

    public function changeCountry(Country $country)
    {

        $this->country = $country;
        $this->redirectRoute('online.programs', ['server' => $this->server_id, 'country' => $this->country->id]);

    }
}
