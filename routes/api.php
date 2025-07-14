<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/orders/latest', [OrderController::class, 'getLatestOrder']);


Route::get('/orders/latest', [OrderController::class, 'getLatestOrder']);
Route::get('/orders', [OrderController::class, 'bulkpdfdownload']);
Route::get('/orders/latest', [OrderController::class, 'getLatestOrder']);

// use App\Http\Controllers\OrderController;

Route::get('/orders/selected-orders', [OrderController::class, 'selectedOrders'])->name('orders.selected-orders');
