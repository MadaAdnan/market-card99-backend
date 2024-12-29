<?php

namespace App\Http\Livewire\Admin\Bills;

use App\Enums\BillStatusEnum;
use App\Jobs\SendNotificationJob;
use App\Models\Balance;
use App\Models\Bill;
use App\Notifications\SendNotificationDB;

use App\Rpositories\BillRepository;
use Livewire\Component;
use Livewire\WithPagination;

class IndexBillComponent extends Component
{
    use WithPagination;

    public $type = 'pending';
    public $search;

    protected $listeners = ['statusBillIsComplete', 'cancelBillComplete'];

    public function cancelBillComplete($e)
    {
        $bill = Bill::find($e['id']);
        BillRepository::cancelBill($bill);
    }

    public function statusBillIsComplete($e)
    {
        $bill = Bill::find($e['id']);
        BillRepository::complateBill($bill);
        $this->dispatchBrowserEvent('success', ['msg' => 'تم إنهاء الطلب بنجاح']);
    }


    public function render()
    {
        return view('livewire.admin.bills.index-bill-component', [
            'bills' => Bill::when($this->type != null, function ($query) {
                $query->where('status', $this->type);
            })->where(function ($query) {
                $query->where('customer_username', 'like', '%' . $this->search . '%');
                $query->orWhere('id', 'like', '%' . $this->search . '%');
                $query->orWhere('customer_id', 'like', '%' . $this->search . '%');
                $query->orWhere('customer_name', 'like', '%' . $this->search . '%');
                $query->orWhere('customer_password', 'like', '%' . $this->search . '%');
                $query->orWhere('data_id', 'like', '%' . $this->search . '%');
                $query->orWhereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            })->latest()->paginate(20),
        ]);
    }

    public function statusBillUpdate($status, $id)
    {

        $this->dispatchBrowserEvent('changeStatusBill', ['id' => $id, 'status' => $status]);
    }

    public function changeStatus($event)
    {
        $status = $event['status'];
        $id = $event['id'];
        $info = isset($event['note']) ? $event['note'] : '';
        $bill = Bill::find($id);
        if ($bill == null) {
            $this->dispatchBrowserEvent('error', ['msg' => 'لحدث خطأ في الطلب']);
        }
        \DB::beginTransaction();
        try {
            if ($bill->status != BillStatusEnum::CANCEL && $status == BillStatusEnum::CANCEL->value) {
                if ($bill->ratio > 0 && $bill->user->user != null) {
                    $bill->user->user->balances()->create([
                        'total' => $bill->user->user->balance - $bill->ratio,
                        'debit' => $bill->ratio,
                        'info' => 'إلغاء أرباح طلب الزبون ' . $bill->user->name,
                    ]);
                }
                $bill->user->balances()->create([
                    'credit' => $bill->total_price,
                    'info' => 'إعادة قيمة طلب ' . $bill->product->name,
                    'total' => $bill->user->balance + $bill->total_price
                ]);
                $data['title'] = 'إلغاء الطلب';
                $data['body'] = 'تم إلغاء طلبك ' . $bill->product->name;
                $data['route'] = route('invoices.show', ['invoice' => $bill->invoice->id]);
                $bill->update(['status' => BillStatusEnum::CANCEL->value, 'cancel_note' => $info]);
                $bill->invoice->update(['status' => BillStatusEnum::CANCEL->value]);
            } else {
                $data['title'] = 'إتمام الطلب';
                $data['body'] = 'تم إتمام طلبك ' . $bill->product->name;
                $data['route'] = route('invoices.show', ['invoice' => $bill->invoice->id]);
                if ($bill->ratio > 0 && $bill->user->user != null && $bill->status != BillStatusEnum::COMPLETE) {
                    $bill->user->user->balances()->create([
                        'total' => $bill->user->user->balance + $bill->ratio,
                        'credit' => $bill->ratio,
                        'info' => ' أرباح طلب الزبون ' . $bill->user->name . 'رقم الطلب' . $bill->id,
                    ]);
                }
                $bill->update(['status' => $status, 'cancel_note' => $info]);
                $bill->invoice->update(['status' => $status]);
            }


            \DB::commit();
            $bill->user->notify(new SendNotificationDB($data));
            $job = new SendNotificationJob([$bill->user], $data);
            dispatch($job);
            $this->dispatchBrowserEvent('success', ['msg' => 'تم تغيير حالة الطلب إلى ' . $status]);

        } catch (\Exception | \Error $e) {
            \DB::rollBack();
            $this->dispatchBrowserEvent('error', ['msg' => $e->getMessage()]);
        }
    }
}
