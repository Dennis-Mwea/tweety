<?php

namespace App\Http\Controllers\Api;

use App\User;

class ProfileRepliesController extends BaseApiController
{
    public function show(User $user)
    {
        return $this->sendResponse(
            $user->replies()->jsonPaginate(10)
        );
    }
}
