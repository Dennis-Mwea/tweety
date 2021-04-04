<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Reply;

class RepliesController extends Controller
{
    public function show(Reply $reply)
    {
        return $reply->children()->paginate(5);
    }
}
