<?php

namespace App\Http\Livewire\Dashboard\Servers;

use App\Models\Server;
use Livewire\Component;

class SortableServer extends Component
{
    public function render()
    {
        return view('livewire.dashboard.servers.sortable-server',[
            'servers'=>Server::orderBy('sort')->get(),
        ]);
    }

    public function updateTaskOrder($list){
        foreach ($list as $item){
            Server::whereId($item['value'])->update(['sort'=>$item['order']]);
        }
    }
}
