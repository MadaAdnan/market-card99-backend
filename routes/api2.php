<?php


Route::prefix('v2')->middleware([\App\Http\Middleware\IsCloseApi::class])->group(function () {
    Route::post('login', [\App\Http\Controllers\Api2\UserController::class, 'login']);
    Route::post('register', [\App\Http\Controllers\Api2\UserController::class, 'store']);
    Route::resource('categories', \App\Http\Controllers\Api2\CategoryController::class)->only(['index', 'show']);
    Route::get('get-category/{id}', [\App\Http\Controllers\Api2\CategoryController::class, 'getCategoryById']);
    Route::resource('settings', \App\Http\Controllers\Api2\SettingController::class)->only(['index']);
    Route::resource('departments', \App\Http\Controllers\Api2\SubCategoryController::class)->only(['show']);
    Route::resource('products', \App\Http\Controllers\Api2\ProductController::class)->only(['show','index']);
    Route::get('discounts', [\App\Http\Controllers\Api2\ProductController::class, 'discount']);
    Route::resource('sliders', \App\Http\Controllers\Api2\SliderController::class)->only(['index']);
    Route::resource('sections', \App\Http\Controllers\Api2\DepartmentController::class)->only('index', 'show');
    Route::middleware(['auth:sanctum', \App\Http\Middleware\IsActiveUserApiMiddleware::class])->group(function () {
        Route::resource('banks', \App\Http\Controllers\Api2\BankController::class)->only(['index', 'store']);
        Route::post('profiles', [\App\Http\Controllers\Api2\UserController::class, 'update']);
        Route::post('buyNumber/{program}', [\App\Http\Controllers\Api2\DepartmentController::class, 'buyNumber']);
        Route::post('set-password', [\App\Http\Controllers\Api2\UserController::class, 'setPassword']);
        Route::post('set-hash', [\App\Http\Controllers\Api2\UserController::class, 'setHash']);
        Route::get('profiles', [\App\Http\Controllers\Api2\UserController::class, 'index']);
        Route::resource('notifications', \App\Http\Controllers\Api2\NotificationController::class);
        Route::resource('asks', \App\Http\Controllers\Api2\AskController::class);
        Route::resource('groups', \App\Http\Controllers\Api2\GroupController::class);
        Route::resource('coupons', \App\Http\Controllers\Api2\CouponController::class);
        Route::resource('bills', \App\Http\Controllers\Api2\BillController::class)->only(['show', 'store', 'index']);
        Route::post('check-player', [\App\Http\Controllers\Api2\BillController::class, 'checkPlayer']);
        Route::post('check-player-api', [\App\Http\Controllers\Api2\BillController::class, 'checkPlayerApi']);
        Route::post('bayFromApi', [\App\Http\Controllers\Api2\BillController::class, 'bayFromApi']);
        Route::resource('orders', \App\Http\Controllers\Api2\OrderController::class)->only(['show', 'index']);
        Route::resource('points', \App\Http\Controllers\Api2\PointController::class)->only(['index']);
        Route::get('convert-balance', [\App\Http\Controllers\Api2\PointController::class, 'convertToBalance']);
        Route::resource('balances', \App\Http\Controllers\Api2\BalanceController::class)->only(['index']);
        Route::resource('charges', \App\Http\Controllers\Api2\ChargeController::class)->only(['index', 'store']);
        Route::post('chargesByCode', [\App\Http\Controllers\Api2\ChargeController::class, 'chargesByCode']);
        Route::get('sync-data', [\App\Http\Controllers\Api2\DepartmentController::class, 'syncData']);
        Route::get('get-products', [\App\Http\Controllers\Api2\DepartmentController::class, 'getProduct']);
        Route::get('sync-online', [\App\Http\Controllers\Api2\OnlineController::class, 'getProduct']);
    });
});
