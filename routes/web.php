<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return "Hello World";
});

Route::controller(PostController::class)->prefix('posts')->group(function () {
    Route::get('/', 'index')->name('posts.index');
    Route::get('/filter/{commentCount}', 'filter')->name('posts.filter');
    Route::post('/', 'store')->name('posts.store');
    Route::get('/{id}', 'show')->name('posts.show');
    Route::get('/{id}/edit', 'edit')->name('posts.edit');
    Route::put('/{id}', 'update')->name('posts.update');
    Route::delete('/{id}', 'destroy')->name('posts.destroy');
});

Route::controller(CommentController::class)->prefix('comments')->group(function () {
    Route::post('/', 'store')->name('comments.store');
    Route::get('/', 'index')->name('comments.index');
    Route::put('/{id}', 'update')->name('comments.update');
    Route::delete('/{id}', 'destroy')->name('comments.destroy');
});

Route::controller(CourseController::class)->prefix('courses')->group(function () {
    Route::get('/', 'index')->name('course.index');
    Route::post('/', 'store')->name('course.store');
    Route::get('/{id}', 'show')->name('course.show');
    Route::get('/{id}/edit', 'edit')->name('courses.edit');
    Route::put('/{id}', 'update')->name('courses.update');
    Route::delete('/{id}', 'destroy')->name('courses.destroy');
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
