<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\PurchaseController;
use App\Http\Controllers\Api\V1\SupplierController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth:sanctum'], function () {
    // User routes
    Route::resource('/users', UserController::class)->except(['edit']);

    // Category routes
    Route::resource('/categories', CategoryController::class)->except(['edit']);

    // Product routes
    Route::resource('/products', ProductController::class)->except(['edit']);

    // Supplier routes
    Route::resource('/suppliers', SupplierController::class)->except(['edit']);

    // Purchase routes
    Route::resource('/purchases', PurchaseController::class)->except(['edit']);
});
