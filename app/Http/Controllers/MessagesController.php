<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\MessageSent;
use App\Message;
use App\User;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Chat $chat)
    {
        return Message::where('chat_id', $chat->id)
            ->latest()
            ->paginate(10);
    }

    public function store(Chat $chat, Request $request)
    {
        $message = current_user()->messages()->create([
            'message' => $request->input('message'),
            'chat_id' => $chat->id,
        ])->load('sender');

        $chat->messages()->attach($message);
        $chat->touch();

        broadcast(new MessageSent(current_user(), $message, $chat))->toOthers();

        return response(['status' => 'Message sent.', 'message' => $message]);
    }

    public function update(Chat $chat, User $user)
    {
        $chat->userMessages($user)->markAsRead();

        return ['status' => 'Message read.'];
    }
}
