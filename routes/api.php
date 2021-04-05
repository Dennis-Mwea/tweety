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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'Api\AuthController@logout');

    Route::get('/tweets', 'Api\TweetsController@index');
    Route::post('/tweets', 'Api\TweetsController@store');
    Route::get('/tweets/{tweet}/replies', 'Api\RepliesController@index');
    Route::post('/tweets/{tweet}/like', 'Api\TweetLikesController@store');
    Route::delete('/tweets/{tweet}/dislike', 'Api\TweetLikesController@destroy');
    Route::delete('/tweets/{tweet}', 'Api\TweetsController@destroy');

    Route::post('/replies/{reply}/like', 'Api\ReplyLikesController@store');
    Route::delete('/replies/{reply}/dislike', 'Api\ReplyLikesController@destroy');
    Route::post('/tweets/{tweet}/reply', 'Api\RepliesController@store');
    Route::post('/tweets/{tweet}/reply', 'Api\RepliesController@store');
    Route::get('/replies/{reply}', 'Api\RepliesController@show');
    Route::get('replies/{reply}/children/json', 'Api\ChildrenRepliesController@jsonShow');

    Route::get('/profile/avatar', 'Api\UserAvatarController@show');
    Route::post('/profile-images', 'Api\UserAvatarController@store');
    Route::get('/profile', 'Api\ProfilesController@index');
    Route::get('/profiles/{user}', 'Api\ProfilesController@show');
    Route::get('/profiles/{user}/tweets', 'Api\ProfileTweetsController@show');
    Route::post('/profiles/{user}/follow', 'Api\FollowsController@store');
    Route::patch('/profiles/{user}', 'Api\ProfilesController@update');
    Route::delete('/replies/{reply}', 'Api\RepliesController@destroy');

    Route::get('/explore', 'Api\ExploreController');

    Route::patch('/auth/email', 'Api\EmailController@update');
    Route::patch('/auth/password', 'Api\PasswordController@update');

    Route::get('/profiles/{user}/following', 'Api\FollowsController@show')
        ->name('api-show-following');
    Route::get('/profiles/{user}/followers', 'Api\FollowsController@show');
    Route::get('/profiles/{user}/replies', 'Api\ProfileRepliesController@show');

    Route::get('/notification-counts', 'Api\NotificationCountsController');
    Route::get('/notifications', 'Api\NotificationsController@index');

    Route::get('search', 'Api\SearchController@show');

    Route::get('/mention', 'Api\MentionUsersController');
});
