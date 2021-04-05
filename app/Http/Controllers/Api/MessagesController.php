<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Events\MessageSent;
use App\Message;

class MessagesController extends BaseApiController
{
    public function get(Chat $chat)
    {
        return $this->sendResponse(Message::where('chat_id', $chat->id)
            ->latest()
            ->paginate(20));
    }

    public function store(Chat $chat)
    {
        request()->validate([
            'message' => 'required|string'
        ]);

        $message = auth()->user()->messages()->create([
            'message' => request('message'),
            'chat_id' => $chat->id,
        ])->load('sender');

        $chat->messages()->attach($message);

        $chat->touch();

        broadcast(new MessageSent(auth()->user(), $message, $chat))->toOthers();

        return $this->sendResponse($message, 'Message Sent!', 201);
    }
}
