<?php

namespace App;

use App\Events\TweetWasPublished;
use App\Traits\Likable;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class Tweet extends Model
{
    use Likable;

    protected $fillable = [
        'body', 'user_id', 'image'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($tweet) {
            event(new TweetWasPublished($tweet));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyAttribute($body)
    {
        return Purify::clean($body);
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace(
            '/@([\w\-\.]+)/',
            '<a href="/profiles/$1" class="text-blue-500 hover:underline">$0</a>',
            $body
        );
    }

    public function getThreadedReplies()
    {
        return $this->Replies()->with('owner')->get()->threaded();
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'tweet_id');
    }

    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }

    public function path()
    {
        return "/tweets/{$this->id}";
    }

    public function showTweet()
    {
        return static::where('tweet_id', $this->id)->withLikes($this->id)->first();
    }
}
