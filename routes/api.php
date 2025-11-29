<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomPriceController;
// Anda bisa menghapus use statement di bawah ini jika tidak ada route lain yang menggunakannya
// use App\Http\Controllers\Api\RoomPriceController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// KOSONGKAN ATAU HAPUS ROUTE HARGA DARI SINI

Route::get('/room-prices/month', [RoomPriceController::class, 'getPricesForMonth'])
     ->name('api.room-prices.month');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
