<?php

namespace App\Http\Controllers\Api;

class UserAvatarController extends BaseApiController
{
    public function show()
    {
        return $this->sendResponse(['avatar' => auth()->user()->avatar]);
    }
}
