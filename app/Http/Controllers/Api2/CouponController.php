<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api2\CouponResource;
use App\Http\Resources\Api2\DoneCouponResource;
use App\Http\Resources\Api2\UserResource;
use App\Models\Coupon;
use App\Support\HelperSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $coupons= Coupon::whereNull('user_id')->where('status', true)->groupBy('price')->orderByDesc('price','asc')->get()->sortBy('price');
       $myCharge=Coupon::where('user_id',auth()->id())->orderBy('updated_at','desc')->paginate(20);
       return HelperSupport::sendData(['coupons'=>CouponResource::collection($coupons),'charges'=>DoneCouponResource::collection($myCharge),'total_page'=>$myCharge->lastPage()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            if(auth()->user()->is_hash && $request->old!=auth()->user()->hash){
                throw new \Exception( 'يرجى إدخال كلمة الشراء بشكل صحيح');
            }

            $coupon = Coupon::whereNull('user_id')->where('status', true)->find($request->coupon_id);
            if (auth()->user()->balance < $coupon->price) {
                throw new \Exception( 'ليس لديك رصيد كافي لشراء هذا الكوبون');
            }
            $coupon->update([
                'user_id'=>auth()->id(),
            ]);
            auth()->user()->balances()->create([
                'debit' => $coupon->price,
                'credit' => 0,
                'info' => 'شراء كوبون رقم '.$coupon->id,
                'total' => auth()->user()->balance-$coupon->price,
            ]);
            DB::commit();
           return HelperSupport::sendData(['msg' =>'تم شراء الكوبون بنجاح','user'=>new UserResource(auth()->user())]);
        }catch (\Exception $e){
            DB::rollBack();
            HelperSupport::SendError( ['msg' =>$e->getMessage()]);
        }
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
}
