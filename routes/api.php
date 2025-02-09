<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
     
Route::middleware(['throttle'])->group( function () {
    Route::post('login', [AuthController::class, 'signin']);
    Route::post('login_mobile', [AuthController::class, 'signin_mobile']);
    Route::post('register', [AuthController::class, 'signup']);
});
     
Route::middleware(['auth:sanctum', 'throttle'])->group( function () {
    Route::get('index', [HomeController::class,'index']);
    Route::get('findCustomerById/{id}', [HomeController::class,'findCustomer']);
    Route::get('findAllCustomer', [HomeController::class,'findAllCustomer']);
});