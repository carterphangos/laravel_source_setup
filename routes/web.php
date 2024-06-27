<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::get('/google', 'redirectToGoogle')->name('auth.google');
    Route::get('/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
});
