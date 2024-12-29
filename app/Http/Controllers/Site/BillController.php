<?php

namespace App\Http\Controllers\Site;

use App\Enums\BillStatusEnum;
use App\Exports\BillExport;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Invoice;
use Excel;
use Illuminate\Http\Request;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$invoices=Invoice::where('user_id',auth()->id())->latest()->paginate(20);
        return view('site.bills');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        return view('site.bill_show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        return Excel::download(new BillExport($invoice), uniqid() . '.xlsx');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $invoice)
    {
        if ($invoice->status == BillStatusEnum::PENDING && $invoice->api_id == null) {
            \DB::beginTransaction();
            try {
                $invoice->update(['status' => BillStatusEnum::REQUEST_CANCEL]);
                $invoice->bills()->update(['status' => BillStatusEnum::REQUEST_CANCEL]);
                \DB::commit();
                return redirect()->back()->with('success', 'تم طلب إلغاء الفاتورة');
            } catch (\Exception | \Error $e) {
                \DB::rollBack();
                return redirect()->back()->with('error', 'حدث خطأ يرجى المحاولة من جديد');
            }

        }
        return redirect()->back()->with('error', 'لا يمكن إرسال طلب إلغاء');
    }
}
