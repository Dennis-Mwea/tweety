<?php

use Illuminate\Support\Str;

function current_user()
{
    return request()->user();
}

function get_notification_view($notificationType)
{
    return Str::of($notificationType)->substr(18, Str::of($notificationType)->length());
}

function get_notification_color($notificationType)
{
    $colors = [
        'App\Notifications\YouWereFollowed' => 'border-blue-500',
        'App\Notifications\YouWereMentioned' => 'border-purple-500',
        'App\Notifications\RecentlyTweeted' => 'border-teal-500',
        'App\Notifications\TweetWasLiked' => 'border-blue-600',
        'App\Notifications\TweetWasDisliked' => 'border-red-500',
        'App\Notifications\ReceivedNewReply' => 'border-indigo-500',
    ];

    return $colors[$notificationType];
}
