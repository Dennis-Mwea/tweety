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
        'App\Notifications\YouWereFollowed' => 'border-blue-400',
        'App\Notifications\YouWereMentioned' => 'border-purple-400',
        'App\Notifications\RecentlyTweeted' => 'border-teal-400'
    ];

    return $colors[$notificationType];
}
