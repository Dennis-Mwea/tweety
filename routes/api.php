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
Route::post('/logout', 'Api\AuthController@logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tweets', 'Api\TweetsController@index');
    Route::post('/tweets', 'Api\TweetsController@store');
    Route::get('/tweets/{tweet}/replies', 'Api\RepliesController@index');
    Route::get('/replies/{reply}/children/json', 'Api\RepliesController@jsonShow');
    Route::post('/tweets/{tweet}/like', 'Api\TweetLikesController@store');
    Route::delete('/tweets/{tweet}/dislike', 'Api\TweetLikesController@destroy');
    Route::delete('/tweets/{tweet}', 'Api\TweetsController@destroy');

    Route::post('/replies/{reply}/like', 'Api\ReplyLikesController@store');
    Route::delete('/replies/{reply}/dislike', 'Api\ReplyLikesController@destroy');
    Route::post('/tweets/{tweet}/reply', 'Api\RepliesController@store');
    Route::post('/tweets/{tweet}/reply', 'Api\RepliesController@store');

    Route::get('/profile/avatar', 'Api\UserAvatarController@show');
    Route::get('/profile', 'Api\ProfilesController@index');
    Route::get('/profiles/{user}', 'Api\ProfilesController@show');
    Route::get('/profiles/{user}/tweets', 'Api\ProfileTweetsController@show');
    Route::post('/profiles/{user}/follow', 'Api\FollowsController@store');
    Route::patch('/profiles/{user}', 'Api\ProfilesController@update');
    Route::delete('/replies/{reply}', 'Api\RepliesController@destroy');

    Route::get('/explore', 'Api\ExploreController');
});
