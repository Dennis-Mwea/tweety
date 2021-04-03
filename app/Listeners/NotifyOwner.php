<?php

namespace App\Listeners;

use App\Events\TweetReceivedNewReply;
use App\Notifications\ReceivedNewReply;

class NotifyOwner
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
        $owner = $hasParent ? $event->reply->parent->owner : $event->reply->tweet->user;

        $owner->notify(new ReceivedNewReply($event->reply->tweet, $event->reply, $isTweet = !$hasParent));
    }
}
