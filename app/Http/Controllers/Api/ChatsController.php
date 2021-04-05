<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\User;

class ChatsController extends BaseApiController
{
    public function index()
    {
        return $this->sendResponse(current_user()->chats()->latest('chats.updated_at')->paginate(10));
    }

    public function show(User $user)
    {
        $chat = $this->findOrCreateChatRoom($user);

        return $this->sendResponse($chat);
    }

    public function findOrCreateChatRoom(User $user)
    {
        $chat = current_user()->chats->filter(function ($chat) use ($user) {
            if ($chat->participants->contains($user)) {
                return $chat;
            }

            return null;
        })->first();

        if (!$chat) {
            $chat = Chat::create();

            $chat->participants()->attach([auth()->id(), $user->id]);
        }

        return $chat;
    }
}
