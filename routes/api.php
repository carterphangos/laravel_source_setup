<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(PostController::class)->prefix('posts')->group(function () {
    Route::get('/', 'index')->name('posts.index');
    Route::post('/t', 'store')->name('posts.store');
    Route::get('/{id}', 'show')->name('posts.show');
    Route::get('/{id}/edit', 'edit')->name('posts.edit');
    Route::put('/{id}', 'update')->name('posts.update');
    Route::delete('/{id}', 'destroy')->name('posts.destroy');
});

Route::controller(CommentController::class)->prefix('comments')->group(function () {
    Route::get('/', 'index')->name('comments.index');
    Route::post('/', 'store')->name('comments.store');
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

