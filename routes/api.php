<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware(['auth:sanctum', 'role:admin'])->resource('products', ProductController::class);

Route::middleware(['auth:sanctum', 'role:admin|client'])->get('/products', [ProductController::class, 'index']);
Route::middleware(['auth:sanctum', 'role:admin'])->post('/products', [ProductController::class, 'store']);
Route::middleware(['auth:sanctum', 'role:admin'])->get('/products/{id}', [ProductController::class,'show']);
Route::middleware(['auth:sanctum', 'role:admin'])->put('/products/{id}', [ProductController::class, 'update']);
Route::middleware(['auth:sanctum', 'role:admin'])->delete('/products/{id}', [ProductController::class,'destroy']);


Route::middleware(['auth:sanctum', 'role:client'])->post('/orders', [OrderController::class,'store']);
Route::middleware(['auth:sanctum', 'role:client'])->delete('/orders/{id}', [OrderController::class,'destroy']);
Route::middleware(['auth:sanctum', 'role:client'])->get('/orders', [OrderController::class,'show']);