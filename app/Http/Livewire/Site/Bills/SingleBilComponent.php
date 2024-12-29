<?php

namespace App\Http\Livewire\Site\Bills;

use App\Enums\BillStatusEnum;
use App\FromApi\Drd3;
use App\FromApi\EkoCard;
use App\FromApi\LifeCash;
use App\FromApi\SpeedCard;
use App\Jobs\SendNotificationJob;
use App\Models\Balance;
use App\Models\Bill;
use App\Models\Setting;
use App\Notifications\SendNotificationDB;
use App\Rpositories\BillRepository;
use Livewire\Component;

class SingleBilComponent extends Component
{
    public Bill $bill;
    public Setting $setting;

    public function mount(Bill $bill)
    {
        $this->bill = $bill;
        $this->setting = Setting::first();
    }

    public function render()
    {
        return view('livewire.site.bills.single-bil-component');
    }

    public function check()
    {

        switch ($this->bill->api) {
            case 'life-cash':
                $service = new LifeCash($this->setting);

                break;
            case 'speed-card':
                $service = new SpeedCard($this->setting);

                break;
            case 'eko':
                $service = new EkoCard($this->setting);
                break;
            case 'drd3':
                $service = new Drd3($this->setting);
                break;
        }
        $service->checkStatus($this->bill);
    }


}
