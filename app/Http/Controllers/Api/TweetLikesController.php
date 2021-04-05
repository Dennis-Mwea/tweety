<?php

namespace App\Http\Controllers\Api;

use App\Like;
use App\Tweet;

class TweetLikesController extends BaseApiController
{
    public function store(Tweet $tweet)
    {
        $result = $tweet->like(current_user());

        if (!$result instanceof Like) {
            return $this->sendResponse($this->removeResponse());
        }
        return $this->sendResponse($this->likeResponse());
    }

    public function removeResponse()
    {
        return [
            'liked' => false,
            'disliked' => false,
            'removed' => true,
        ];
    }

    public function likeResponse()
    {
        return [
            'liked' => true,
            'disliked' => false,
            'removed' => false,
        ];
    }

    public function destroy(Tweet $tweet)
    {
        $result = $tweet->dislike(current_user());

        if (!$result instanceof Like) {
            return $this->sendResponse($this->removeResponse());
        }
        return $this->sendResponse($this->dislikeResponse());
    }

    public function dislikeResponse()
    {
        return [
            'liked' => false,
            'disliked' => true,
            'removed' => false,
        ];
    }
}
