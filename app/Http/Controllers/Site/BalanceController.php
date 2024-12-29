<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Point;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $balances=auth()->user()->balances()->latest()->paginate(50);
        return view('site.balances',compact('balances'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function convertPoint2Balance(){
        if(auth()->check() && auth()->user()->getTotalPoint()>5){
            \DB::beginTransaction();
            try {
                $total_point=auth()->user()->getTotalPoint();
                Point::create([
                    'user_id'=>auth()->id(),
                    'debit'=>$total_point,
                    'credit'=>0,
                    'info'=>'تحويل إلى رصيد'
                ]);
                Balance::create([
                    'user_id'=>auth()->id(),
                    'credit'=>$total_point,
                    'debit'=>0,
                    'info'=>'تحويل النقاط إلى رصيد',
                    'total'=>auth()->user()->balance+$total_point,
                ]);

                \DB::commit();
                return back()->with('success','تم التحويل بنجاح');
            }catch (\Exception|\Error $e){
                \DB::rollBack();
                return back()->with('error','حدث خطأ أثناء التحويل');
            }

        }
        return back();
    }
}
