<?php

namespace App\Http\Controllers\Api;

class MentionUsersController extends BaseApiController
{
    public function __invoke()
    {
        $search = request('username');

        return current_user()->follows()
            ->where('username', 'LIKE', "$$search%")
            ->take(5)
            ->get();
    }
}
