<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('password/forgot', 'Api\ForgotPasswordController');

Route::middleware('auth:sanctum')
    ->namespace('Api')
    ->group(function () {
        Route::post('/logout', 'AuthController@logout');

        Route::get('/tweets', 'TweetsController@index');
        Route::post('/tweets', 'TweetsController@store');
        Route::get('/tweets/{tweet}/replies', 'RepliesController@index');
        Route::post('/tweets/{tweet}/like', 'TweetLikesController@store');
        Route::delete('/tweets/{tweet}/dislike', 'TweetLikesController@destroy');
        Route::delete('/tweets/{tweet}', 'TweetsController@destroy');

        Route::post('/replies/{reply}/like', 'ReplyLikesController@store');
        Route::delete('/replies/{reply}/dislike', 'ReplyLikesController@destroy');
        Route::post('/tweets/{tweet}/reply', 'RepliesController@store');
        Route::post('/tweets/{tweet}/reply', 'RepliesController@store');
        Route::get('/replies/{reply}', 'RepliesController@show');
        Route::get('replies/{reply}/children/json', 'ChildrenRepliesController@jsonShow');

        Route::get('/profile/avatar', 'UserAvatarController@show');
        Route::post('/profile-images', 'UserAvatarController@store');
        Route::get('/profile', 'ProfilesController@index');
        Route::get('/profiles/{user}', 'ProfilesController@show');
        Route::get('/profiles/{user}/tweets', 'ProfileTweetsController@show');
        Route::post('/profiles/{user}/follow', 'FollowsController@store');
        Route::patch('/profiles/{user}', 'ProfilesController@update');
        Route::delete('/replies/{reply}', 'RepliesController@destroy');

        Route::get('/explore', 'ExploreController');

        Route::patch('/auth/email', 'EmailController@update');
        Route::patch('/auth/password', 'PasswordController@update');

        Route::get('/profiles/{user}/following', 'FollowsController@show')
            ->name('api-show-following');
        Route::get('/profiles/{user}/followers', 'FollowsController@show');
        Route::get('/profiles/{user}/replies', 'ProfileRepliesController@show');

        Route::get('/notification-counts', 'NotificationCountsController');
        Route::get('/notifications', 'NotificationsController@index');

        Route::get('search', 'SearchController@show');

        Route::get('/mention', 'MentionUsersController');

        Route::get('/chat', 'ChatsController@index');
        Route::get('/chat/{chat}/messages', 'MessagesController@get');
    });
