<?php

namespace App\Http\Controllers\Api;

use App\User;

class ProfileTweetsController extends BaseApiController
{
    public function show(User $user)
    {
        return $this->sendResponse($user->tweets()->jsonPaginate(10));
    }
}
