<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegisterationController;
use App\Http\Controllers\API\ArticleManagementController;
use App\Http\Controllers\API\UserFeedSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'generateToken'])->name('auth.login');
    Route::post('signup', [RegisterationController::class, 'store'])->name('auth.signup');
});


Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::put('password/reset', [AuthController::class, 'passwordReset'])->name('auth.password.reset');
    });

    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleManagementController::class, 'index'])->name('articles.index');
        Route::get('search', [ArticleManagementController::class, 'search'])->name('articles.search');
    });

    Route::get('article/{article}', [ArticleManagementController::class, 'show'])->name('articles.show');

    Route::prefix('setting')->group(function () {
        Route::post('/', [UserFeedSettingController::class, 'create'])->name('user.feed.setting.create');
    });

    Route::get('user/feed', [UserFeedSettingController::class, 'index'])->name('user.feed.index');

});
