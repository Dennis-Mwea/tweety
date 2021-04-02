<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TweetController extends Controller
{
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
