<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Ask;
use App\Models\Presint;
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
        abort_if(auth()->user()->email=='market@gmail.com',403);
        $asks = Ask::whereIsActive('active')->get();
        return view('site.presint', compact('asks'));
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
        abort_if(auth()->user()->email=='market@gmail.com',403);
      //  dd($request->all());
        $this->validate($request,[
            'answers.*'=>'required',
        ]);
        $countPresent=Presint::where(['user_id'=>auth()->id(),'status' => true])->count();
        if($countPresent>0){
            return back()->with('error','لقد قمت بالطلب مسبقا وطلبك قيد المراجعة');
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
            return back()->with('success','تم إرسال الطلب بنجاح سيتم التواصل معك في أقرب وقت');
        } catch (\Exception | \Error $exception) {
            \DB::rollBack();
            return back()->with('warning','حدث خطأ أثناء المعالجة يرجى المحاولة لاحقا');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
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
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
