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

    public function destroy(Reply $reply)
    {
        $this->authorize('edit', $reply->owner);

        $reply->delete();

        if (request()->expectsJson())
            return response(['status' => 'Reply deleted']);

        return back();
    }
}
