<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\CategoryController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/register', 'register')->name('auth.register');
    Route::post('/forgot-password', 'send')->name('auth.email');
    Route::post('/reset-password', 'reset')->name('auth.reset');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/update', 'update')->name('auth.update');
        Route::post('/logout', 'logout')->name('auth.logout');
        Route::post('/refresh', 'refresh')->name('auth.refresh')->middleware('ability:access-token');
    });

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::post('/{id}', 'update')->name('user.update');
        Route::put('/password', 'updatePassword')->name('user.password');
    });
    Route::apiResource('users', UserController::class)->except(['update']);

    Route::apiResource('announcements', AnnouncementController::class);

    Route::apiResource('categories', CategoryController::class);

    Route::controller(TrackingController::class)->prefix('trackings')->group(function () {
        Route::post('/', 'store')->name('track.store');
        Route::get('/check/{id}', 'check')->name('track.check');
        Route::get('/daily-stats', 'dailyStats')->name('track.daily-stats');
        Route::get('/announ-stats', 'announStats')->name('track.announ-stats');
    });

    Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
        Route::put('/all/{id}', 'updateAll')->name('notifications.updateAll');
    });
    Route::apiResource('notifications', NotificationController::class)->except(['index', 'show', 'store']);
});
