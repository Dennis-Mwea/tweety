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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/tweets', 'TweetController@index')->name('home');
    Route::post('/tweets', 'TweetController@store')->name('createTweet');

    Route::get('/profiles/{user}','ProfilesController@show')->name('profile');
    Route::get('/profiles/{user}/edit','ProfilesController@edit')->name('profile.edit');
    Route::post('/profiles/{user}/follow','FollowsController@store')->name('follows');
});
