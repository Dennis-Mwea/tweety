<?php

namespace App\Listeners;

use App\Events\TweetReceivedNewReply;
use App\Notifications\ReceivedNewReply;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOwner implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param TweetReceivedNewReply $event
     * @return void
     */
    public function handle(TweetReceivedNewReply $event)
    {
        $hasParent = $event->reply->parent_id !== null;
        $owner = $owner = $this->getOwner($event);

        $owner->notify(new ReceivedNewReply($event->reply->tweet, $event->reply, $isTweet = !$hasParent));
    }

    public function getOwner($event)
    {
        $hasParent = $event->reply->parent_id !== null;

        return $hasParent ? $event->reply->parent->owner : $event->reply->tweet->user;
    }

    public function shouldQueue($event)
    {
        $owner = $this->getOwner($event);

        return $event->reply->owner->isNot($owner);
    }
}
