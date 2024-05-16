<?php

use Illuminate\Support\Facades\Route;

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
