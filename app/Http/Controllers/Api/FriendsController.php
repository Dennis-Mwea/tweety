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
        return cache()->remember('friends', 43200, function () {
            return current_user()->follows->take(10);
        });
    }
}
