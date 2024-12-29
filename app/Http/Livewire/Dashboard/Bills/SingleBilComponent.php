<?php

namespace App\Http\Livewire\Dashboard\Bills;

use App\Enums\BillStatusEnum;
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

    //protected $listeners = ['changeStatus'];
    public function mount($bill)
    {
        $this->bill = $bill;
    }

    public function render()
    {
        return view('livewire.dashboard.bills.single-bil-component');
    }

    public function statusComplete()
    {
        BillRepository::complateBill($this->bill);
    }

    public function statusCancel()
    {
        BillRepository::cancelBill($this->bill);

    }

    public function check()
    {
        $setting = Setting::first();
        switch ($this->bill->api) {
            case 'life-cash':
                $service = new LifeCash($setting);
                break;
            case 'speed-card':
                $service = new SpeedCard($setting);
                break;
            case 'eko':
                $service = new EkoCard($setting);
                break;
        }

        try {
            $service->checkStatus($this->bill);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('error', ['msg' => $e->getMessage() . $e->getLine()]);

        }
    }

    public function CancelBill()
    {


        try {
            if ($this->bill->status != BillStatusEnum::CANCEL) {
                BillRepository::cancelBill($this->bill,'تم رفض الطلب');
            }
        } catch (\Exception | \Error $e) {

            $this->dispatchBrowserEvent('error', ['msg' => $e->getMessage()]);
        }
    }





}
