<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BillExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $data;

    public function __construct(Invoice $invoice)
    {
        $this->data = [
            'bills' =>
                $invoice->bills
        ];
    }

    public function view(): View
    {
        return view('site.export_bill', $this->data);
    }
}
