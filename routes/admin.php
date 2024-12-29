<?php


use App\Http\Controllers\Admin\BalanceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CountryRelationServer;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\ProgramRelationServer;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;


Route::middleware(['auth', 'role:super_admin|partner'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::middleware('role:super_admin')->group(function(){
Route::get('user-balance',function (){
    return (new \App\Exports\UserBalanceExport)->download('balances.xlsx');


})->name('export.balance');
        Route::resource('presints', \App\Http\Controllers\Dashboard\PresintController::class)->only(['index', 'show']);
        Route::resource('asks', \App\Http\Controllers\Dashboard\AskController::class)->only(['index', 'create', 'edit']);
        Route::resource('servers', ServerController::class)->only(['index', 'create', 'edit']);
        Route::resource('countries', CountryController::class)->only(['index', 'create', 'edit']);
        Route::resource('programs', ProgramController::class)->only(['index', 'create', 'edit']);
        Route::resource('relations_program', ProgramRelationServer::class)->only(['edit']);
        Route::resource('relations_country', CountryRelationServer::class)->only(['edit']);
        Route::resource('orders', OrderController::class)->only([ 'index']);
        Route::resource('bills', \App\Http\Controllers\Admin\BillController::class)->only([ 'index']);
        // Categories
        Route::resource('sliders', SliderController::class)->only(['index', 'create', 'edit']);
        Route::resource('groups', GroupController::class)->only([ 'index','create','edit']);


        Route::resource('categories', CategoryController::class)->only([ 'index','create','edit','show']);
        Route::resource('products', ProductController::class)->only([ 'index','create','edit']);
        Route::resource('settings', SettingController::class)->only([ 'index']);
        Route::resource('partners', PartnerController::class)->only([ 'index','create','edit']);
        Route::resource('items', ItemController::class)->only([ 'create']);
        Route::resource('banks', \App\Http\Controllers\Admin\BankController::class)->only([ 'index','create','edit','show']);
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponeController::class)->only([ 'index','create']);
        Route::resource('charges', \App\Http\Controllers\Admin\ChargeController::class)->only([ 'index']);
    });
    Route::get('/', [UserController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class)->only(['index', 'create', 'edit', 'show']);
    Route::resource('balances', BalanceController::class)->only([ 'create']);
    Route::resource('statistics', \App\Http\Controllers\Admin\StatisticController::class)->only([ 'index']);
    Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class)->only([ 'index','create']);
    Route::get('steps/{user}', [\App\Http\Controllers\Admin\UserBillController::class,'show'])->name('user-bill.show');
    Route::get('count',function (){
        $server=\App\Models\Server::where('code',\App\Helpers\GoregSms::class)->first();
        $response=\Illuminate\Support\Facades\Http::withBody(json_encode([
            'action'=>'GET_SERVICES',
            'key'=>$server->api,
        ]),'Application/json')->post('https://goreg-sms.com/api/legacy');
        $gorg_countries=[];
        if($response->successful()){
            foreach ($response['countryList'] as $key=>$country){
                $gorg_countries[$key]=['country'=>$country['country'],'count'=>isset($country['operatorMap']['qcell']['wa'])?$country['operatorMap']['qcell']['wa']:0];
            }
        }
        return $gorg_countries;
    })->name('count');
});
