<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    public function index()
    {
        return view('tweets.home', [
            'tweets' => request()->user()->timeline()
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'body' => 'required'
        ]);

        request()->user()->tweets()->create([
            'body' => $attributes['body']
        ]);

        redirect('home');
    }
}
