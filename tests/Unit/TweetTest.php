<?php

namespace Tests\Unit;

use App\Notifications\RecentlyTweeted;
use App\Tweet;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Tweet
     */
    private $tweet;

    public function testATweetHasAPath()
    {
        self::assertEquals("/tweets/{$this->tweet->id}", $this->tweet->path());
    }

    public function testATweetHasACreator()
    {
        self::assertInstanceOf(User::class, $this->tweet->user);
    }

    public function testATweetHasReplies()
    {
        self::assertInstanceOf(Collection::class, $this->tweet->replies);
    }

    public function testAReplyCanBeAddedToATweet()
    {
        $this->tweet->addReply([
            'body' => 'Foo Bar',
            'user_id' => 1
        ]);

        self::assertCount(1, $this->tweet->replies);
    }

    public function testATweetNotifiesAllFollowersWhenPublished()
    {
        Notification::fake();

        $creator = create(User::class);
        $follower = create(User::class);
        $this->signIn($follower);

        $follower->follow($creator);

        create(Tweet::class, [
            'user_id' => $creator->id
        ]);

        Notification::assertSentTo($follower, RecentlyTweeted::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tweet = create(Tweet::class);
    }
}
