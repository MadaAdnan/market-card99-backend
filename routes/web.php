<?php

use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Ladumor\OneSignal\OneSignal;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Route::get('getToken',function(){
   $users=\App\Models\User::whereNull('token')->get();
    foreach ($users as $user) {
        $token = $user->createToken('user')->plainTextToken;
        $user->update([
            'token'=>$token,
        ]);
   }
    return 'success';
});*/

/*Route::get('al-amanah',function(){
    if(!auth()->check()){
        $user=\App\Models\User::whereEmail('market5@gmail.com')->first();
        auth()->login($user);
    }

    return redirect()->route('home');
});

Route::get('al-thiqa',function(){
    if(!auth()->check()){
        $user=\App\Models\User::whereEmail('market10@gmail.com')->first();
        if($user){
            auth()->login($user);
        }

    }

    return redirect()->route('home');
});

Route::middleware([\App\Http\Middleware\IsOpenMiddleware::class])->group(function(){
    Route::get('/', [\App\Http\Controllers\Site\HomeController::class, 'index'])->name('home');
    Route::get('categories/{category}', [\App\Http\Controllers\Site\HomeController::class, 'show'])->name('category.show');
    Route::get('products/{category}', [\App\Http\Controllers\Site\ProductController::class, 'index'])->name('products.index');
    Route::get('show/{product}', [\App\Http\Controllers\Site\ProductController::class, 'show'])->name('products.show');


});
Route::get('debug',function(){
   return $apps=Server::findOrFail(1)->programs;
});

Route::middleware(['auth',\App\Http\Middleware\IsOpenMiddleware::class])->group(function () {
    Route::middleware(['is_active'])->group(function () {
        Route::resource('/asks', \App\Http\Controllers\Site\AskController::class)->only(['index','store']);
        Route::get('/signout', [\App\Http\Controllers\Site\HomeController::class, 'signout'])->name('sign-out');
        Route::get('home', [\App\Http\Controllers\Site\HomeController::class, 'index'])->name('home.index');
//        Route::post('orders/{product}', [\App\Http\Controllers\Site\ProductController::class, 'store'])->name('products.orders');
        Route::get('online', [\App\Http\Controllers\Site\OnlineController::class, 'index'])->name('online.index');
        Route::get('online2', [\App\Http\Controllers\Site\Sim90Controller::class, 'index'])->name('online2.index');
        Route::get('online/orders', [\App\Http\Controllers\Site\OnlineController::class, 'orders'])->name('online.orders');
        Route::get('programs/{server}/{country}', [\App\Http\Controllers\Site\OnlineController::class, 'programs'])->name('online.programs');
//        Route::resource('partners', \App\Http\Controllers\Site\PartnerController::class)->only('index');
        Route::resource('invoices', \App\Http\Controllers\Site\BillController::class)->only('index', 'show', 'edit');
        Route::get('orders/{invoice}/cancel', [\App\Http\Controllers\Site\BillController::class, 'destroy'])->name('orders.cancel');
        Route::resource('balances', \App\Http\Controllers\Site\BalanceController::class)->only('index');
        Route::resource('points', \App\Http\Controllers\Site\PointController::class)->only('index');
        Route::resource('levels', \App\Http\Controllers\Site\LevelController::class)->only('index');
        Route::get('convert', [\App\Http\Controllers\Site\BalanceController::class,'convertPoint2Balance'])->name('convert');
        Route::resource('notifications', \App\Http\Controllers\Site\NotificationsController::class)->only('index');
        Route::resource('profile', \App\Http\Controllers\Site\ProfileController::class)->only('index');
        Route::resource('charges', \App\Http\Controllers\Site\ChargeController::class)->only('index');
        Route::resource('coupons', \App\Http\Controllers\Site\CouponController::class)->only('index');
        Route::resource('offers', \App\Http\Controllers\Site\OfferController::class)->only('index');

    });
});

require __DIR__ . '/auth.php';*/

/*Route::any('{name}',function(){
    return redirect('https://market-card99.com');
})->where('name','(?!admin).*');*/
Route::get('/getProduct/', function () {
    $response = Http::withToken('d6b1a1713b76d45811427a546bf2e6cf')->get('https://as7abcard.com/api/v1/products');
    if ($response->successful()) {
        return $response->json();
    }
});

Route::get('checkLogin', function () {

});
