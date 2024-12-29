<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('test', fn() => \App\Models\Server::first()->programs[0]->pivot->price);
Route::get('registerToken/{id}', function ($id) {
    \App\Models\User::find($id)->update(['device_token' => \request()->query('token')]);
    return 'success';
});

Route::prefix('v1')->group(function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'store']);


    ###########################################
    ###########################################
    Route::middleware('auth:sanctum')->group(function () {
//        Route::apiResource('/servers', \App\Http\Controllers\Api\ServerController::class);
//        Route::get('/getServers', [\App\Http\Controllers\Api\ServerController::class, 'getServers']);
//        Route::get('/programs/{server}', [\App\Http\Controllers\Api\ServerController::class, 'programsAndCountry']);
//        Route::get('/getPrice/{server}/{program}', [\App\Http\Controllers\Api\ServerController::class, 'getPrice']);
//        Route::get('/orders/{server}/{country}/{program}', [\App\Http\Controllers\Api\OrderController::class, 'store']);
        Route::resource('/orders', \App\Http\Controllers\Api\OrderController::class)->only(['index', 'show']);
        Route::resource('categories', \App\Http\Controllers\Api\CategoryController::class)->only('index', 'show');
        Route::resource('balances', \App\Http\Controllers\Api\BalanceController::class)->only('index');
        Route::post('bills/{id}', [\App\Http\Controllers\Api\BillController::class,'store']);
        Route::get('bills', [\App\Http\Controllers\Api\BillController::class,'index']);
        Route::get('bills/{id}', [\App\Http\Controllers\Api\BillController::class,'show']);

    });

});


require_once 'api2.php';
