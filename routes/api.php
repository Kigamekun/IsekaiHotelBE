<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HotelController,CheckoutController,RoomController,FoodController,OrderRoomController,OrderFoodController};
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


Route::post("invoice", [CheckoutController::class, 'create']);


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
    });

    Route::prefix('food')->group(function () {
        Route::get('/', [FoodController::class,'index'])->name('api.food.index');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/checkToken', [AuthController::class, 'checkToken']);
        Route::prefix('room')->group(function () {
            Route::post('/store', [RoomController::class,'store'])->name('api.room.store');
            Route::post('/search', [RoomController::class,'search'])->name('api.room.search');
            Route::put('/update/{id}', [RoomController::class,'update'])->name('api.room.update');
            Route::delete('/delete/{id}', [RoomController::class,'destroy'])->name('api.room.delete');
        });
        Route::prefix('food')->group(function () {
            Route::post('/store', [FoodController::class,'store'])->name('api.food.store');
            Route::put('/update/{id}', [FoodController::class,'update'])->name('api.food.update');
            Route::delete('/delete/{id}', [FoodController::class,'destroy'])->name('api.food.delete');
        });

        Route::prefix('order_room')->group(function () {
            Route::get('/', [OrderRoomController::class,'index'])->name('api.order_room.index');
            Route::post('/booking', [OrderRoomController::class,'booking'])->name('api.order_room.booking');
            Route::post('/pay_room/{id}', [OrderRoomController::class,'pay_room'])->name('api.order_room.pay_room');
            Route::get('/cancel_room/{id}', [OrderRoomController::class,'cancel_room'])->name('api.order_room.cancel_room');
            Route::post('/store', [OrderRoomController::class,'store'])->name('api.order_room.store');
            Route::put('/update/{id}', [OrderRoomController::class,'update'])->name('api.order_room.update');
            Route::delete('/delete/{id}', [OrderRoomController::class,'destroy'])->name('api.order_room.delete');
        });

        Route::prefix('order_food')->group(function () {
            Route::get('/', [OrderFoodController::class,'index'])->name('api.order_food.index');
            Route::post('/order', [OrderFoodController::class,'order'])->name('api.order_food.order');
            Route::get('/pay_food/{id}', [OrderFoodController::class,'pay_food'])->name('api.order_food.pay_food');
            Route::get('/cancel_food/{id}', [OrderFoodController::class,'cancel_food'])->name('api.order_food.cancel_food');
            Route::post('/store', [OrderFoodController::class,'store'])->name('api.order_food.store');
            Route::put('/update/{id}', [OrderFoodController::class,'update'])->name('api.order_food.update');
            Route::delete('/delete/{id}', [OrderFoodController::class,'destroy'])->name('api.order_food.delete');
        });
    });
});
