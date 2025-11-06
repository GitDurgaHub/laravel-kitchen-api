<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store']);
Route::get('/orders/active', [App\Http\Controllers\OrderController::class, 'active']);
Route::post('/orders/{order}/complete', [App\Http\Controllers\OrderController::class, 'complete']);
