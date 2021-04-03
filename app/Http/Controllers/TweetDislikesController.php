<?php

namespace App\Http\Controllers;

use App\Tweet;

class TweetDislikesController extends Controller
{
    public function store(Tweet $tweet)
    {
        $tweet->dislike(current_user());
    }

    public function destroy(Tweet $tweet)
    {
        $tweet->removeDislike(current_user());
    }
}
