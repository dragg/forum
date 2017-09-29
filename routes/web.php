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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('threads', 'ThreadsController', ['only' => ['index', 'create', 'store']]);
Route::get('threads/{channel}', 'ThreadsController@index')->name('channel.threads');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
Route::resource('threads.replies', 'RepliesController', ['only' => ['store']]);