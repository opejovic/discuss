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

Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('/', 'ThreadsController@index');
Route::get('threads', ['as' => 'threads.index', 'uses' => 'ThreadsController@index']);
Route::get('threads/{thread}/replies', ['as' => 'replies.index', 'uses' => 'RepliesController@index']);


Route::middleware('auth')->group(function () {
    Route::get('threads/create', ['as' => 'threads.create', 'uses' => 'ThreadsController@create']);
    Route::post('threads', ['as' => 'threads.store',  'uses' => 'ThreadsController@store']);
    Route::delete('threads/{thread}', ['as' => 'threads.destroy', 'uses' => 'ThreadsController@destroy']);

    Route::post('threads/{thread}/replies', ['as' => 'replies.store', 'uses' => 'RepliesController@store']);
    Route::get('replies/{reply}', ['as' => 'replies.edit', 'uses' => 'RepliesController@edit']);
    Route::patch('replies/{reply}', ['as' => 'replies.update', 'uses' => 'RepliesController@update']);
    Route::delete('replies/{reply}',['as' => 'replies.destroy', 'uses' => 'RepliesController@destroy']);

    Route::post('threads/{thread}/likes', ['as' => 'likes.store', 'uses' => 'ThreadLikesController@store']);
    Route::delete('threads/{thread}/likes', ['as' => 'likes.destroy', 'uses' => 'ThreadLikesController@destroy']);

    Route::post('threads/{thread}/subscribe', ['as' => 'threads.subscribe', 'uses' => 'ThreadSubscriptionsController@store']);
    Route::delete('threads/{thread}/unsubscribe', ['as' => 'threads.unsubscribe', 'uses' => 'ThreadSubscriptionsController@destroy']);

    Route::get('notifications', ['as' => 'notifications.index', 'uses' => 'UserNotificationsController@index']);
    Route::delete('notifications/{notification}', ['as' => 'notifications.destroy', 'uses' => 'UserNotificationsController@destroy']);
});

Route::get('threads/{channel}', 'ThreadsController@index');
Route::get('threads/{channel}/{thread}', ['as' => 'threads.show', 'uses' => 'ThreadsController@show']);

Route::get('profiles/{user}', ['as' => 'profile', 'uses' => 'ProfilesController@show']);
