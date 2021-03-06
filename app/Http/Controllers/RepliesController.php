<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Tweet;

class RepliesController extends Controller
{
    public function index(Tweet $tweet)
    {
        return $tweet->getThreadedReplies();
    }

    public function store(Tweet $tweet)
    {
        $attributes = request()->validate([
            'body' => 'required|max:255',
        ]);

        return $tweet->addReply([
            'body' => $attributes['body'],
            'user_id' => auth()->id(),
            'parent_id' => request('parent_id', null),
        ]);
    }

    public function show(Tweet $tweet, Reply $reply)
    {
        return view('replies.show', [
            'tweet' => $tweet,
            'reply' => $reply
        ]);
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('edit', $reply->owner);

        $reply->delete();

        if (request()->expectsJson())
            return response(['status' => 'Reply deleted']);

        return back();
    }
}
