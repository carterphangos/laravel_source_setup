<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'registerUser');
    Route::post('/login', 'loginUser');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(PostController::class)->prefix('posts')->group(function () {
        Route::get('/', 'index')->name('posts.index');
        Route::post('/', 'store')->name('posts.store');
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
    Route::prefix('users')->group(function () {
        Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    });
});
