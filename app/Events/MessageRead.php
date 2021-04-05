<?php

namespace App\Events;

use App\Chat;
use App\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that read the message
     *
     * @var \App\User
     */
    public $user;


    public $chat;

    public $read_at;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Chat $chat
     */
    public function __construct(User $user, Chat $chat)
    {
        $this->user = $user;

        $this->chat = $chat;

        $this->read_at = Carbon::now();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PresenceChannel
     */
    public function broadcastOn()
    {
        return new PresenceChannel("chat.{$this->chat->id}");
    }
}
