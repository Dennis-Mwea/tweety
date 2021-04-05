<?php

namespace App\Http\Controllers\Api;

use App\Reply;

class ChildrenRepliesController extends BaseApiController
{
    public function show(Reply $reply)
    {
        return $this->sendResponse($reply->children()->paginate(5));
    }

    public function jsonShow(Reply $reply)
    {
        return $this->sendResponse($reply->children()->jsonPaginate(5));
    }
}
