<?php

namespace App\Http\Livewire\Admin;

use App\Models\Program;
use App\Models\Server;
use Livewire\Component;

class ProgramRelationServerComponent extends Component
{


    public Server $server;
    public $programs_ids = [];
    public $programs_code = [];
    public $programs_price = [];

    public function mount(Server $server)
    {
        $this->server = $server;

        foreach ($server->programs as $program) {
            $this->programs_ids[] = $program->id;
            $this->programs_code[$program->id] = $program->pivot->code;
            $this->programs_price[$program->id] = $program->pivot->price;
        }
    }

    public function render()
    {
        return view('livewire.admin.program-relation-server-component', [
            'programs' => Program::all(),
        ]);
    }

    public function submit()
    {
        $this->server->programs()->sync([]);
        foreach ($this->programs_ids as $id) {
            $this->server->programs()->attach($id, [
                'code' => $this->programs_code[$id],
                'price' => $this->programs_price[$id],
            ]);
        }
        $this->dispatchBrowserEvent('success', ['msg' => 'تم ربط التطبيقات بنجاح']);
    }
}
