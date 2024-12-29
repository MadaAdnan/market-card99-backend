<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api2\BankResource;
use App\Http\Resources\Api2\ChargeResource;
use App\Models\Bank;
use App\Models\Recharge;
use App\Support\HelperSupport;
use Illuminate\Http\Request;

class BankController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks=Bank::where('banks.is_active',true)->get();
        $charges=Recharge::where('user_id',auth()->id())->latest()->paginate(25);
        return HelperSupport::sendData(['banks'=>BankResource::collection($banks),
            'charges'=>ChargeResource::collection($charges),
            'total_page'=>$charges->lastPage()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
