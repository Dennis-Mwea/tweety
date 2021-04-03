<?php

namespace App\Providers;

use App\Events\TweetReceivedNewReply;
use App\Events\TweetWasPublished;
use App\Listeners\NotifyFollowers;
use App\Listeners\NotifyMentionedUsers;
use App\Listeners\NotifyOwner;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        TweetWasPublished::class => [
            NotifyMentionedUsers::class,
            NotifyFollowers::class,
        ],

        TweetReceivedNewReply::class => [
            NotifyMentionedUsers::class,
            NotifyOwner::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
