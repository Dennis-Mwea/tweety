<?php

namespace App\Events;

use App\Message;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message.
     *
     * @var User
     */
    public $sender;

    /**
     * Message details
     *
     * @var Message
     */
    public $message;

    public $chat;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Message $message
     * @param $chat
     */
    public function __construct(Authenticatable $user, Message $message, $chat)
    {
        $this->sender = $user;
        $this->message = $message;
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel("chat.{$this->chat->id}");
    }
}
