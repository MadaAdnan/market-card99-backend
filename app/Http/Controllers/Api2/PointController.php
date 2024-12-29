<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api2\BalanceResource;
use App\Http\Resources\Api2\UserResource;
use App\Models\Balance;
use App\Models\Point;
use App\Models\User;
use App\Support\HelperSupport;
use Illuminate\Http\Request;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $points = Point::where('user_id', auth()->id())->latest()->paginate(25);

        return HelperSupport::sendData(['points' => BalanceResource::collection($points), 'user' => new UserResource(auth()->user()), 'total_page' => $points->lastPage()]);
    }

    public function convertToBalance()
    {
        $point = Point::where('user_id', auth()->id())->selectRaw('SUM(credit-debit) as total')->first();
        $points = Point::where('user_id', auth()->id())->latest()->paginate(25);
        if($point?->total>5){
            \DB::beginTransaction();
            try{
                Point::create([
                    'user_id'=>auth()->id(),
                    'info'=>'تحويل إلى رصيد',
                    'debit'=>$point->total,
                    'credit'=>0,
                ]);
                Balance::create([
                    'user_id'=>auth()->id(),
                    'info'=>'تحويل النقاط إلى رصيد',
                    'credit'=>$point->total,
                    'debit'=>0,
                    'total'=>auth()->user()->balance+$point->total
                ]);
                \DB::commit();
                return HelperSupport::sendData(['points' => BalanceResource::collection($points), 'user' => new UserResource(auth()->user()), 'total_page' => $points->lastPage()]);

            }catch (\Exception | \Error $e){
                \DB::rollBack();
                return HelperSupport::sendData(['points' => BalanceResource::collection($points), 'user' => new UserResource(auth()->user()), 'total_page' => $points->lastPage()],code: 201);

            }
        }

        return HelperSupport::sendData(['points' => BalanceResource::collection($points), 'user' => new UserResource(auth()->user()), 'total_page' => $points->lastPage()]);
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
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
