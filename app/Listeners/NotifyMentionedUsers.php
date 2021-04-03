<?php

namespace App\Listeners;

use App\Events\TweetWasPublished;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
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
     * @param TweetWasPublished $event
     * @return void
     */
    public function handle($event)
    {
        tap($event->subject(), function ($subject) {
            User::whereIn('username', $this->mentionedUsers($subject->body))
                ->get()->each->notify(new YouWereMentioned($subject));
        });
    }

    private function mentionedUsers($body)
    {
        preg_match_all('/@([\w\-\.]+)/', $body, $matches);

        return $matches[1];
    }
}
