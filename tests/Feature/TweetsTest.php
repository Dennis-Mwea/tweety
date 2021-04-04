<?php

namespace Tests\Feature;

use App\Notifications\RecentlyTweeted;
use App\Reply;
use App\Tweet;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TweetsTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestsCannotManageTweets()
    {
        $tweet = create(Tweet::class);

        $this->get('/tweets')->assertRedirect('login');
        $this->get($tweet->path())->assertRedirect('login');
        $this->post('/tweets', $tweet->toArray())->assertRedirect('login');
    }

    public function testAUserCanCreateATweet()
    {
        $this->signIn();

        $this->get('/tweets')->assertStatus(200);
        $this->followingRedirects()
            ->post('/tweets', $attributes = make(Tweet::class)->toArray())
            ->assertSee($attributes['body']);
    }

    public function testAUserCanAttachAPhotoToATweetOnTweetCreation()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('POST', route('createTweet'), [
            'body' => 'body',
            'image' => $file = UploadedFile::fake()->image('image.jpg')
        ]);

        Storage::disk('public')->assertExists('tweet-images/' . $file->hashName());
    }

    public function testUnAuthorizedUsersCannotDeleteTweets()
    {
        $tweet = create(Tweet::class);

        $this->delete($tweet->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($tweet->path())->assertStatus(403);
    }

    public function testAUserCanDeleteAProject()
    {
        $tweet = create(Tweet::class);

        $this->actingAs($tweet->user)
            ->delete($tweet->path())
            ->assertRedirect('/tweets');

        $this->assertDatabaseMissing('tweets', $tweet->only('id'));
    }

    public function testATweetRequiresABody()
    {
        $this->signIn();

        $attributes = make(Tweet::class, ['body' => ''])->toArray();

        $this->post('/tweets', $attributes)->assertSessionHasErrors('body');
    }

    public function testAUserCanViewASingleTweet()
    {
        $this->signIn();

        $tweet = create(Tweet::class);

        $this->get($tweet->path())
            ->assertSee($tweet->body);
    }

    public function testAUserCanSeeAllRepliesForAGivenTweet()
    {
        $this->signIn();

        $tweet = create(Tweet::class);

        create(Reply::class, ['tweet_id' => $tweet->id], 2);
        $response = $this->getJson($tweet->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
    }

    public function testAUserCanSeeAllChildrenRepliesForAGivenTweet()
    {
        $this->signIn();

        $tweet = create(Tweet::class);

        $reply = create(Reply::class, ['tweet_id' => $tweet->id]);
        create(Reply::class, ['tweet_id' => $tweet->id, 'parent_id' => $reply->id], 2);

        $response = $this->getJson("api/replies/{$reply->id}/children")->json();

        $this->assertCount(2, $response['data']);
    }

    public function testAUserTimelineIncludesTweetsOfFollowingUsers()
    {
        Notification::fake();

        $user = create(User::class);
        $creator = create(User::class);
        $this->signIn($user);
        $user->follow($creator);

        $tweet = create(Tweet::class, ['user_id' => $creator->id]);

        Notification::assertSentTo($user, RecentlyTweeted::class);

        $this->actingAs($user)
            ->get('/tweets')
            ->assertSee($tweet->body);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling();
    }
}
