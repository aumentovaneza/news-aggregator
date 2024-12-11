<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('auth/login'. [AuthController::class, 'generateToken']);

Route::middleware(['auth:sanctum'])->group(function(){
    // Add protected endpoints here
    // Route::get('/users', [UserController::class, 'index]);
});

