<?php

namespace App\Http\Controllers;

use App\User;

class FollowsController extends Controller
{
    public function store(User $user)
    {
        $user = request()->user()->toggleFollow($user);

        return $user;
    }
}
