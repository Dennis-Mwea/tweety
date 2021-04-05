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
    Route::get('/tweets/search', 'SearchController@show')->name('show-search');
    Route::post('/tweets/{tweet}/like', 'TweetsLikesController@store')->name('likeTweet');
    Route::delete('/tweets/{tweet}/like', 'TweetsLikesController@destroy')->name('dislikeTweet');
    Route::delete('/tweets/{tweet}', 'TweetController@destroy')->name('delete-tweet');
    Route::get('/tweets/{tweet}', 'TweetController@show')->name('tweets.show');
    Route::get('/tweets/{tweet}/replies/{reply}', 'RepliesController@show')->name('tweets.replies.show');

    Route::post('/tweets/{tweet}/reply', 'RepliesController@store')->name('tweets.replies.store');
    Route::get('/tweets/{tweet}/replies', 'RepliesController@index')->name('replies');
    Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('delete-reply');
    Route::get('/api/replies/{reply}/children', 'Api\RepliesController@show');
    Route::post('/replies/{reply}/like', 'ReplyLikesController@store')->name('like-reply');
    Route::delete('/replies/{reply}/like', 'ReplyLikesController@destroy')->name('dislike-reply');

    Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
    Route::get('/profiles/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
    Route::patch('/profiles/{user}', 'ProfilesController@update')->name('profile.edit');
    Route::post('/profiles/{user}/follow', 'FollowsController@store')->name('follows');

    Route::get('/explore', 'ExploreController')->name('explore');

    Route::get('/api/friends', 'FriendsController@index');
    Route::get('/api/search-friends', 'Api\FriendsController@index');

    Route::get('/profiles/{user}/followers', 'FollowsController@show')->name('show-followers');
    Route::get('/profiles/{user}/following', 'FollowsController@show')->name('show-following');

    Route::get('/notifications', 'NotificationsController@index')->name('notifications');

    Route::get('/search', 'SearchController@show')->name('show-search');

    Route::get('/profiles/{user}/settings', 'SettingsController@edit')
        ->middleware('can:edit,user')->name('account-settings');
    Route::get('/profiles/{user}/password/edit', 'PasswordController@edit')
        ->middleware('can:edit,user')->name('edit-password');
    Route::patch('/profiles/{user}/password', 'PasswordController@update')
        ->middleware('can:edit,user')->name('update-password');

    Route::get('/profiles/{user}/email/edit', 'EmailController@edit')
        ->middleware('can:edit,user', 'password.confirm')->name('edit-email');
    Route::patch('/profiles/{user}/email', 'EmailController@update')
        ->middleware('can:edit,user')->name('update-email');

    Route::get('/chats', 'ChatsController@index')->name('chat');
    Route::get('/chat/{user}', 'ChatsController@show')->name('show-chat');
    Route::get('/chat/{chat}/messages', 'MessagesController@get');
    Route::post('/chat/{chat}/messages', 'MessagesController@store');
    Route::patch('/chat/{chat}/messages/{username}/read', 'MessagesController@update');
});
