<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
    protected $shouldFlash;

    public function index()
    {
        return view('tweets.home', [
            'tweets' => request()->user()->timeline()
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'body' => 'required',
            'image' => 'sometimes|nullable|image',
        ]);
        $attributes['user_id'] = auth()->id();
        if (request('image'))
            $attributes['image'] = request()->file('image')->storePublicly('tweet-images', 'public');

        request()->user()->tweets()->create($attributes);

        if (request()->wantsJson()) {
            return ['message' => '/tweets'];
        }

        return redirect()->route('home')->with('flash', 'Your tweet has been published!');
    }

    public function destroy(Tweet $tweet)
    {
        $this->authorize('edit', $tweet->user);
        if (Storage::disk('public')->exists($tweet->image))
            Storage::disk('public')->delete($tweet->image);

        $tweet->delete();

        if (request()->wantsJson()) {
            return ['message' => '/tweets'];
        }

        return redirect('home');
    }
}
