<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlyProgramrResource;
use App\Http\Resources\OnlyServerResource;
use App\Http\Resources\ServerResource;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers=Server::active()->get();
        return  response()->json(['status'=>'success','servers'=>ServerResource::collection($servers)]);
    }
    public function getServers()
    {
        $servers=Server::active()->get();
        return  response()->json(['status'=>'success','servers'=>OnlyServerResource::collection($servers)]);
    }

    public function programsAndCountry(Server $server)
    {

        return  response()->json(['status'=>'success','programs'=>OnlyProgramrResource::collection($server->programs),'countries'=>OnlyProgramrResource::collection($server->countries)]);
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
    public function show(Server $server)
    {
        if($server->is_active=='inactive'){
            return  response()->json(['status'=>'error','msg'=>'السيرفر غير متوفر حاليا']);

        }
        return  response()->json(['status'=>'success','servers'=>new ServerResource($server)]);
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

    public function getPrice(Server $server,$program){
        $app= $server->programs()->findOrFail($program);
        return response()->json(['status'=>'success','price'=>$app->pivot->price]);
    }
}
