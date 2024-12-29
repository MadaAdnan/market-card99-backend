<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api2\NotificationResource;
use App\Http\Resources\Api2\SettingResource;
use App\Http\Resources\Api2\UserResource;
use App\Models\Setting;
use App\Models\User;
use App\Support\HelperSupport;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting=Setting::first();
        $notification=null;
        if (auth()->check()) {

            $notification = DatabaseNotification::whereNotifiableType(User::class)->whereNotifiableId(auth()->id())->whereNull('read_at')->where('data->admin', 1)->latest()->first();

        }
        return HelperSupport::sendData([
            'setting'=>new SettingResource($setting),
            'notification'=>$notification?new NotificationResource($notification):[],
            'user'=>auth()->check()?new UserResource(auth()->user()):null,
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
