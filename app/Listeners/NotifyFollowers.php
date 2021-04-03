<?php

namespace App\Listeners;

use App\Notifications\RecentlyTweeted;

class NotifyFollowers
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
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $event->tweet->user->followers->each->notify(new RecentlyTweeted($event->tweet));
    }
}
