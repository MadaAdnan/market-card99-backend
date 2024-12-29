<?php

namespace App\Http\Livewire\Admin\Groups;

use App\Models\Group;
use Livewire\Component;

class IndexGroupComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.groups.index-group-component',[
            'groups'=>Group::paginate(15),
        ]);
    }
}
