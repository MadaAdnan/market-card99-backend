<?php

namespace App\Observers;

use App\Filament\Resources\BillResource;
use App\Models\Bill;

class BillObserve
{
    /**
     * Handle the Bill "created" event.
     *
     * @param \App\Models\Bill $bill
     * @return void
     */
    public function created(Bill $bill)
    {
        //
    }

    /**
     * Handle the Bill "updated" event.
     *
     * @param \App\Models\Bill $bill
     * @return void
     */
    public function updated(Bill $bill)
    {
        if ($bill->isDirty('status') && $bill->user->order_hook != '') {
            try {
                \Http::post($bill->user->order_hook, ['bill' => new \App\Http\Resources\Api2\BillResource($bill), 'password' => md5(strtotime(date('Y-m-d')))]);

            } catch (\Exception | \Error $e) {

            }
        }


    }

    /**
     * Handle the Bill "deleted" event.
     *
     * @param \App\Models\Bill $bill
     * @return void
     */
    public function deleted(Bill $bill)
    {
        //
    }

    /**
     * Handle the Bill "restored" event.
     *
     * @param \App\Models\Bill $bill
     * @return void
     */
    public function restored(Bill $bill)
    {
        //
    }

    /**
     * Handle the Bill "force deleted" event.
     *
     * @param \App\Models\Bill $bill
     * @return void
     */
    public function forceDeleted(Bill $bill)
    {
        //
    }
}
