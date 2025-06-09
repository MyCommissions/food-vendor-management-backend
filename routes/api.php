<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('pending-vendors', [AdminController::class, 'getPendingVendors']);
        Route::post('vendors/{user}/approve', [AdminController::class, 'approveVendor']);
        Route::post('vendors/{user}/reject', [AdminController::class, 'rejectVendor']);
        Route::get('users', [AdminController::class,'getAllUsers']);
        Route::get('users/vendors', [AdminController::class,'getAllVendors']);
        Route::get('users/customers', [AdminController::class,'getAllCustomers']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('store')->group(function () {
        Route::post('create', [StoreController::class, 'createStore']);
    });
});