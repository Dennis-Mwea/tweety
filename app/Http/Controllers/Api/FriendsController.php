<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;

class FriendsController extends Controller
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function index()
    {
        return cache()->rememberForever('friends.' . auth()->id(), function () {
            return current_user()->follows->take(10);
        });
    }
}
