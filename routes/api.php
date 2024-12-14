<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegisterationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'generateToken']);
    Route::post('signup', [RegisterationController::class, 'store']);
});


Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::put('password/reset', [AuthController::class, 'passwordReset']);
    });

    // Add protected endpoints here
    // Route::get('/users', [UserController::class, 'index]);
});
