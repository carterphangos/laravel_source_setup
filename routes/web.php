<?php

use Illuminate\Support\Facades\Route;
use App\Services\LogService;

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

Route::get('/log', function (LogService $logService) {
    $logService->info('This is an info message.');
    $logService->error('This is an error message.', ['error' => 'Something went wrong']);
    $logService->warning('This is an warning message.', ['warning' => 'Something crashed']);
    return 'Logging test.';
});