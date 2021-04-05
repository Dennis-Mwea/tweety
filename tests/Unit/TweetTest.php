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
        $secondFollower = create(User::class);
        $this->signIn($follower);

        $follower->follow($creator);
        $this->signIn($secondFollower);
        $secondFollower->follow($creator);

        create(Tweet::class, [
            'user_id' => $creator->id
        ]);

        Notification::assertSentTo([$follower, $secondFollower], RecentlyTweeted::class);
    }

    public function testATweetWrapsMentionedUsernamesInTheBodyWithinAnchorTagsWithCorrectStylings()
    {
        $tweet = new Tweet([
            'body' => 'Hello @Jane-Doe'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe" class="text-blue-500 hover:underline">@Jane-Doe</a>',
            $tweet->body
        );
    }

    public function testATweetWrapsWebpageUrlInTheBodyWithinAnchorTagsWithCorrectStylings()
    {
        $tweet = new Tweet([
            'body' => 'Check at google.com and https://tunnandaaung.tech'
        ]);

        $this->assertEquals(
            'Check at <a href="google.com" class="text-blue-500 hover:underline">google.com</a> and <a href="https://tunnandaaung.tech" class="text-blue-500 hover:underline" target="_blank" rel="noreferrer noopener">https://tunnandaaung.tech</a>',
            $tweet->body
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tweet = create(Tweet::class);
    }
}
