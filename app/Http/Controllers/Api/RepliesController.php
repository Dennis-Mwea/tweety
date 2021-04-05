<?php

namespace App\Http\Controllers\Api;

use App\Reply;
use App\Tweet;

class RepliesController extends BaseApiController
{
    public function index(Tweet $tweet)
    {
        return $this->sendResponse($tweet->getApiThreadedReplies());
    }

    public function show(Reply $reply)
    {
        return $reply->children()->paginate(5);
    }

    public function jsonShow(Reply $reply)
    {
        return $this->sendResponse($reply->children()->jsonPaginate(3));
    }
}
