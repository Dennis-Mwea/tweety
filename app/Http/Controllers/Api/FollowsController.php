<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Support\Facades\Route;

class FollowsController extends BaseApiController
{
    public function store(User $user)
    {
        return $this->sendResponse(tap(current_user())->toggleFollow($user));
    }

    public function show(User $user)
    {
        if (Route::currentRouteName() == 'api-show-following') {
            return $this->showFollowing($user);
        }

        return $this->showFollowers($user);
    }

    private function showFollowing(User $user)
    {
        return $this->sendResponse($user->follows()->jsonPaginate(15));
    }

    private function showFollowers(User $user)
    {
        return $this->sendResponse($user->followers()->jsonPaginate(15));
    }
}
