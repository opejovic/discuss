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
    Route::delete('threads/{thread}', 'ThreadsController@destroy')->name('threads.destroy');

    Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store')->name('replies.store');
    Route::delete('{thread}/replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');

    Route::post('threads/{thread}/likes', 'ThreadLikesController@store')->name('likes.store');
    Route::delete('threads/{thread}/likes', 'ThreadLikesController@destroy')->name('likes.destroy');
});

Route::get('threads/{channel}', 'ThreadsController@index');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');

Route::get('profiles/{user}', 'ProfilesController@show')->name('profile');
