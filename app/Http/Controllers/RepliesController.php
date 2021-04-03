<?php

namespace App\Http\Controllers;

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

        $tweet->addReply([
            'body' => $attributes['body'],
            'user_id' => auth()->id(),
            'parent_id' => request('parent_id', null),
        ]);

        if (request()->wantsJson()) {
            return ['message' => "/tweets/{$tweet->id}"];
        }

        return back();
    }
}
