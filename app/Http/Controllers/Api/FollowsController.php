<?php

namespace App\Http\Controllers\Api;

use App\User;

class FollowsController extends BaseApiController
{
    public function store(User $user)
    {
        return $this->sendResponse(tap(current_user())->toggleFollow($user));
    }
}
