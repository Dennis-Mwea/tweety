<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    public function testAReplyHasAnOwner()
    {
        $reply = create(Reply::class);

        self::assertInstanceOf(User::class, $reply->owner);
    }

    public function testAReplyHasAPath()
    {
        $reply = create(Reply::class);

        self::assertEquals($reply->tweet->path() . "/replies/{$reply->id}", $reply->path());
    }

    public function testAReplyWrapsMentionedUsernamesInTheBodyWithinAnchorTagsWithCorrectStylings()
    {
        $reply = new Reply([
            'body' => 'Hello @Jane-Doe'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe" class="text-blue-500 hover:underline">@Jane-Doe</a>',
            $reply->body
        );
    }

    public function testAReplyBodyIsSanitizedAutomatically()
    {
        $reply = make(Reply::class, ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);

        $this->assertEquals("<p>This is okay.</p>", $reply->body);
    }

    public function testAReplyCanHaveChildren()
    {
        $parent = create(Reply::class);

        create(Reply::class, ['parent_id' => $parent->id], 2);

        $this->assertCount(2, $parent->children);
    }
}
