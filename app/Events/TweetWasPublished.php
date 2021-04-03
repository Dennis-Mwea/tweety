<?php

namespace App\Events;

use App\Tweet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TweetWasPublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Tweet
     */
    public $tweet;

    /**
     * Create a new event instance.
     *
     * @param Tweet $tweet
     */
    public function __construct(Tweet $tweet)
    {
        $this->tweet = $tweet;
    }

    public function subject()
    {
        return $this->tweet;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
