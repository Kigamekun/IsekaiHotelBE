<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HotelController,CheckoutController,RoomController,FoodController,OrderRoomController,OrderFoodController};
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

Route::get('/', function () {
    return view('welcome');
});


Route::get("/getInvoice/{id_room}", [CheckoutController::class, 'getInvoice']);
