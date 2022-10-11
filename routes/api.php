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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('admin')->middleware(['auth','checkRole:2'])->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

    Route::prefix('kamar')->name('kamar.')->group(function () {
        Route::get('/', 'KamarController@index')->name('index');
        Route::get('/create', 'KamarController@create')->name('create');
        Route::post('/store', 'KamarController@store')->name('store');
        Route::get('/{id}/edit', 'KamarController@edit')->name('edit');
        Route::post('/{id}/update', 'KamarController@update')->name('update');
        Route::get('/{id}/delete', 'KamarController@destroy')->name('delete');
    });
    Route::middleware(['auth','checkRole:0'])->group(function () {
        Route::post('/checkout', 'BookingController@checkout')->name('checkout');
        Route::get('/pay/{code}', 'BookingController@pay')->name('pay');
        Route::get('/cancel-order/{code}', 'BookingController@cancelOrder')->name('cancel-order');
        Route::post('/update-transaction', 'BookingController@updateTransaction')->name('update-transaction');
        Route::get('/transaction', 'BookingController@transaction')->name('transaction');
        Route::get('/transaction-invoice/{code}', 'BookingController@transactionInvoice')->name('transaction-invoice');
        Route::get('/transaction-success', 'BookingController@transactionSuccess')->name('transaction-success');
        Route::get('/transaction-pending', 'BookingController@transactionPending')->name('transaction-pending');
        Route::get('/transaction-error', 'BookingController@transactionError')->name('transaction-error');
        Route::post('/order', 'BookingController@order')->name('order');
        Route::post('/checkout-non-register', 'BookingController@checkoutNonRegister')->name('checkout-non-register');
        Route::get('/invoice/{id}', 'BookingController@invoice')->name('invoice');


    });
});

