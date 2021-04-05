<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Chat;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $chat) {
    $chat = Chat::find($chat);
    if (!is_null($chat) && $chat->participants->contains($user)) {
        return $user;
    }

    return false;
});
