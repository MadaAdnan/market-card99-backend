<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api2\AskResource;
use App\Models\Ask;
use App\Models\Presint;
use App\Support\HelperSupport;
use Illuminate\Http\Request;

class AskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $asks=Ask::where('asks.is_active',true)->orderBy('sortable')->get();
        return HelperSupport::sendData(['asks'=>AskResource::collection($asks)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        foreach ($request->answers as $key=>$value){
          $ask=Ask::find($key);
          if($ask===null){
              continue;
          }

        }

        $countPresent=Presint::where(['user_id'=>auth()->id(),'status' => true])->count();
        if($countPresent>0){
            HelperSupport::SendError(['msg'=>'لقد قمت بالطلب مسبقا وطلبك قيد المراجعة']);
        }
        \DB::beginTransaction();
        try {
            $presint = Presint::create([
                'user_id' => auth()->id(),
                'status'=>true
            ]);
            foreach ($request->answers as $key => $answer) {
                $presint->asks()->syncWithPivotValues([$key],['answer'=>$answer],false);
            }

            \DB::commit();
            return HelperSupport::sendData(['msg'=>'تم إرسال الطلب بنجاح سيتم التواصل معك في أقرب وقت']);
        } catch (\Exception | \Error $exception) {
            \DB::rollBack();
            HelperSupport::SendError(['msg'=>'warning',$exception->getMessage()]);
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
