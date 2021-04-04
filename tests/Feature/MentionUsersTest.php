<?php

namespace Tests\Feature;

use App\Tweet;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_thread_are_notified()
    {
        $john = create(User::class, ['username' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create(User::class, ['username' => 'JaneDoe']);

        $tweet = make(Tweet::class, [
            'body' => 'Hey @JaneDoe check this out.'
        ]);

        $this->post('/tweets', $tweet->toArray());

        $this->assertCount(1, $jane->notifications);

        $this->assertEquals(
            "JohnDoe mentioned you in a tweet.",
            $jane->notifications->first()->data['message']
        );
    }

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create(User::class, ['username' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create(User::class, ['username' => 'JaneDoe']);

        // If we have a tweet
        $tweet = create(\App\Tweet::class);

        // And JohnDoe replies to that tweet and mentions @JaneDoe.
        $reply = make(\App\Reply::class, [
            'body' => 'Hey @JaneDoe check this out.'
        ]);

        $this->json('post', $tweet->path() . '/reply', $reply->toArray());

        // Then @JaneDoe should receive a notification.
        $this->assertCount(1, $jane->notifications);

        $this->assertEquals(
            "JohnDoe mentioned you in a reply.",
            $jane->notifications->first()->data['message']
        );
    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters_from_followings_users()
    {
        Notification::fake();

        $john1 = create(User::class, ['username' => 'johndoe']);
        $john2 = create(User::class, ['username' => 'johndoe2']);
        $jane = create(User::class, ['username' => 'janedoe']);

        $vader = create(User::class, ['username' => 'Vader']);

        $this->signIn($vader);

        $vader->follow($john1);
        $vader->follow($john2);
        $vader->follow($jane);

        $results = $this->json('GET', '/api/search-friends', ['username' => 'john']);

        $this->assertCount(3, $results->json());
    }
}
