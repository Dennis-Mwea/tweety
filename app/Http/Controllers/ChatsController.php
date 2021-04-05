<?php

namespace App\Http\Controllers;

use App\Chat;
use App\User;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(User $user)
    {
        $chat = $this->findOrCreateChatRoom($user);

        $recipient = $user;

        $this->authorize('view', $chat);

        return view('chat.show', compact(['chat', 'recipient']));
    }

    private function findOrCreateChatRoom(User $user)
    {
        $chat = current_user()->chats->filter(function ($chat) use ($user) {
            if ($chat->participants->contains($user)) {
                return $chat;
            }

            return null;
        })->first();

        if (!$chat) {
            $chat = Chat::create();

            $chat->participants()->attach([current_user()->id, $user->id]);
        }

        return $chat;
    }
}
