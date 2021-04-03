<?php

namespace App\Http\Controllers;

use App\Tweet;

class RepliesController extends Controller
{
    public function store(Tweet $tweet)
    {
        $tweet->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
            'parent_id' => request('parent_id', null),
        ]);

        return back();
    }
}
