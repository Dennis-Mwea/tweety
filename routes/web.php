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
    Route::get('/', 'HomeController@index');

    Route::get('/tweets', 'TweetController@index')->name('home');
    Route::post('/tweets', 'TweetController@store')->name('createTweet');
    Route::post('/tweets/{tweet}/like', 'TweetsLikesController@store')->name('likeTweet');
    Route::delete('/tweets/{tweet}/like', 'TweetsLikesController@destroy')->name('dislikeTweet');
    Route::delete('/tweets/{tweet}', 'TweetController@destroy')->name('delete-tweet');
    Route::get('/tweets/{tweet}', 'TweetController@show')->name('tweets.show');
    Extract replies


    Route::post('/tweets/{tweet}/reply', 'RepliesController@store')->name('tweets.replies.store');
    Route::get('tweets/{tweet}/replies', 'RepliesController@index')->name('replies');
    Route::get('/api/replies/{reply}/children', 'Api\RepliesController@show');

    Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
    Route::get('/profiles/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
    Route::patch('/profiles/{user}', 'ProfilesController@update')->name('profile.edit');
    Route::post('/profiles/{user}/follow', 'FollowsController@store')->name('follows');

    Route::get('/explore', 'ExploreController')->name('explore');

    Route::get('/api/friends', 'FriendsController@index');
    Route::get('/api/search-friends', 'Api\FriendsController@index');

    Route::get('/notifications', 'NotificationsController@index')->name('notifications');
});
