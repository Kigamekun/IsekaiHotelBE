<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HotelController,RoomController,FoodController};
use App\Http\Controllers\API\{AuthController};
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);


    });
    Route::prefix('hotel')->group(function () {
        Route::get('/', [HotelController::class,'index'])->name('api.hotel.index');
        Route::post('/store', [HotelController::class,'store'])->name('api.hotel.store');
        Route::put('/update/{id}', [HotelController::class,'update'])->name('api.hotel.update');
        Route::delete('/delete/{id}', [HotelController::class,'destroy'])->name('api.hotel.delete');
    });
    Route::prefix('room')->group(function () {
        Route::get('/', [RoomController::class,'index'])->name('api.room.index');
        Route::post('/store', [RoomController::class,'store'])->name('api.room.store');
        Route::put('/update/{id}', [RoomController::class,'update'])->name('api.room.update');
        Route::delete('/delete/{id}', [RoomController::class,'destroy'])->name('api.room.delete');
    });
    Route::prefix('food')->group(function () {
        Route::get('/', [FoodController::class,'index'])->name('api.food.index');
        Route::post('/store', [FoodController::class,'store'])->name('api.food.store');
        Route::put('/update/{id}', [FoodController::class,'update'])->name('api.food.update');
        Route::delete('/delete/{id}', [FoodController::class,'destroy'])->name('api.food.delete');
    });
});

    // Route::middleware(['auth','checkRole:0'])->group(function () {
    //     Route::post('/checkout', 'BookingController@checkout')->name('checkout');
    //     Route::get('/pay/{code}', 'BookingController@pay')->name('pay');
    //     Route::get('/cancel-order/{code}', 'BookingController@cancelOrder')->name('cancel-order');
    //     Route::post('/update-transaction', 'BookingController@updateTransaction')->name('update-transaction');
    //     Route::get('/transaction', 'BookingController@transaction')->name('transaction');
    //     Route::get('/transaction-invoice/{code}', 'BookingController@transactionInvoice')->name('transaction-invoice');
    //     Route::get('/transaction-success', 'BookingController@transactionSuccess')->name('transaction-success');
    //     Route::get('/transaction-pending', 'BookingController@transactionPending')->name('transaction-pending');
    //     Route::get('/transaction-error', 'BookingController@transactionError')->name('transaction-error');
    //     Route::post('/order', 'BookingController@order')->name('order');
    //     Route::post('/checkout-non-register', 'BookingController@checkoutNonRegister')->name('checkout-non-register');
    //     Route::get('/invoice/{id}', 'BookingController@invoice')->name('invoice');
    // });
