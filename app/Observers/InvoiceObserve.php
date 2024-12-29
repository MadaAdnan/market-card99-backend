<?php

namespace App\Observers;

use App\Enums\BillStatusEnum;
use App\Jobs\SendNotificationJob;
use App\Models\Invoice;
use App\Models\User;
use App\Notifications\SendNotificationDB;

class InvoiceObserve
{
    /**
     * Handle the Invoice "created" event.
     *
     * @param \App\Models\Invoice $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        if ($invoice->status == BillStatusEnum::PENDING) {
            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'super_admin');
            })->get();
            try {

                $data['title'] = 'فاتورة بالإنتظار';
                $data['body'] = $invoice->user->name;
                $data['route'] = route('dashboard.bills.index');
                $job = new SendNotificationJob($users, $data);
                dispatch($job);
            } catch (\Exception | \Error $e) {

            }
        }


    }

    /**
     * Handle the Invoice "updated" event.
     *
     * @param \App\Models\Invoice $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param \App\Models\Invoice $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     *
     * @param \App\Models\Invoice $invoice
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     *
     * @param \App\Models\Invoice $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
        //
    }
}
