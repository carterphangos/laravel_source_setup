<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::group(['prefix' => 'api', 'namespace' => 'App\Http\Controllers\Api'], function () {
//     Route::apiResource('posts', 'PostController');
//     Route::post('comments', 'CommentController@store')->name('comments.store');
//     Route::get('comments', 'CommentController@index')->name('comments.index');
//     Route::put('comments/{id}', 'CommentController@update')->name('comments.update');
//     Route::delete('comments/{id}', 'CommentController@destroy')->name('comments.destroy');
//     Route::apiResource('courses', 'CourseController');
// });
