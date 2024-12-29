<?php

namespace App\Http\Livewire\Site\Notifications;

use Livewire\Component;

class BellComponent extends Component
{
    public function render()
    {
        return view('livewire.site.notifications.bell-component',[
            'count'=>auth()->user()->unreadNotifications->count(),
        ]);
    }
}
