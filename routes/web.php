<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/logged', function () {
    return view('logged-in');
})->middleware('auth:sanctum');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::get('/google', 'redirectToGoogle')->name('auth.google');
    Route::get('/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
});
