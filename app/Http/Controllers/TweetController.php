<?php

namespace App\Http\Controllers;

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

        request()->user()->tweets()->create($attributes);

        if (request()->wantsJson()) {
            return ['message' => '/tweets'];
        }

        return redirect()->route('home')->with('flash', 'Your tweet has been published!');
    }
}
