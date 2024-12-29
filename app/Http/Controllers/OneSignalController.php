<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Kodjunkie\OnesignalPhpSdk\Exceptions\OneSignalException;
use Kodjunkie\OnesignalPhpSdk\OneSignal;

class OneSignalController extends Controller
{
    public function index(){

        try {
            // Initialize the SDK
            // Resolve from the IoC container
            $oneSignal = app()->make('onesignal');
           // dd($oneSignal);

            // Using the API
            // Get all devices
          $response = $oneSignal->device()->getAll('122b8c3e-092c-4e6f-b51c-267936e81f5b', 3, 2);

            // Using the facade, the code above will look like this
            // with "app_id" provided in the config
            $response = OneSignal::device()->get('PLAYER_ID');


        } catch (OneSignalException $exception) {
            dd($exception->getMessage());
        }
    }
}
