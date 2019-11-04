<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('', 'ThreadsController@index');
Route::get('threads', 'ThreadsController@index')->name('threads.index');

Route::middleware('auth')->group(function () {
    Route::get('threads/create', 'ThreadsController@create')->name('threads.create');
    Route::post('threads', 'ThreadsController@store')->name('threads.store');
    Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store')->name('replies.store');

    Route::post('threads/{thread}/likes', 'ThreadLikesController@store')->name('likes.store');
});

Route::get('threads/{channel}', 'ThreadsController@index');

Route::get('threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
