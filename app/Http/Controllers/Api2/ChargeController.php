<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api2\ChargeResource;
use App\Http\Resources\Api2\UserResource;
use App\Models\Balance;
use App\Models\Charge;
use App\Models\Coupon;
use App\Models\Recharge;
use App\Support\HelperSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $this->validate($request,[
            'img'=>'nullable|image'
        ]);

       $recharge= Recharge::create([
            'value'=>$request->value,
            'user_id'=>auth()->id(),
            'info'=>$request->info,
            'bank_id'=>$request->bank_id,
            'status'=>'pending'
        ]);
       if($request->hasFile('img')){
           $recharge->addMedia($request->img)->sanitizingFileName(function ($fileName) {
               return time() . strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
           })->toMediaCollection('image');
       }
       return HelperSupport::sendData(['charge'=> new ChargeResource($recharge)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chargesByCode(Request $request)
    {
        $coupon = Coupon::whereStatus(true)->whereCode(Str::upper($request->code))->first();

        if ($coupon) {
            \DB::beginTransaction();
            try {
                $data=[
                    'status' => false,

                ];
                if($coupon->user_id==null){
                    $data['user_id']= auth()->id();

                }
                $coupon->update($data);
                Balance::create([
                    'credit' => $coupon->price,
                    'debit' => 0,
                    'user_id'=>auth()->id(),
                    'info' => 'شحن عن طريق الكوبون رقم : ' . $coupon->code,
                    'total' => auth()->user()->balance + $coupon->price,
                ]);
                \DB::commit();
                return HelperSupport::sendData(['user'=>new UserResource(auth()->user())]);

            } catch (\Exception $e) {
                \DB::rollBack();
                HelperSupport::SendError( ['msg' => $e->getMessage()]);
            }
        } else {
            HelperSupport::SendError( ['msg' => 'الكوبون غير موجود أو انه تم إستخدامه']);
        }
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
