<?php

namespace App\Http\Controllers\Api;

class ChatsController extends BaseApiController
{
    public function index()
    {
        return $this->sendResponse(current_user()->chats()->latest('chats.updated_at')->paginate(10));
    }
}
