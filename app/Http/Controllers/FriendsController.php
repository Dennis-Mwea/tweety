<?php

namespace App\Http\Controllers;

class FriendsController extends Controller
{
    public function index()
    {
        return request()->user()->follows;
    }
}
