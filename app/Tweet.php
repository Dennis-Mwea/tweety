<?php

namespace App;

use App\Events\TweetReceivedNewReply;
use App\Events\TweetWasPublished;
use App\Traits\Likable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Stevebauman\Purify\Facades\Purify;

/**
 * @method static create(array $attributes)
 */
class Tweet extends Model
{
    use Likable;
    use Searchable;

    protected $fillable = [
        'body', 'user_id', 'image'
    ];

    protected $appends = ['is_liked', 'is_disliked', 'replies_count', 'likes_count', 'dislikes_count'];

    protected $with = ['user'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($tweet) {
            event(new TweetWasPublished($tweet));
        });

        static::deleting(function ($tweet) {
            $tweet->replies->each->delete();
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
        $find = [
            $mentionUserRegex = '/@([\w\-\.]+)/',
            $urlRegex = '/[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/'
        ];
        $replace = [
            '<a href="/profiles/$1" class="text-blue-500 hover:underline">$0</a>',
            '<a href="$0" class="text-blue-500 hover:underline">$0</a>'
        ];

        $this->attributes['body'] = preg_replace(
            $find,
            $replace,
            $body
        );
    }

    public function getImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }

    public function getThreadedReplies()
    {
        return $this->threadedReplies()->paginate(10);
    }

    public function threadedReplies()
    {
        return $this->replies()->with('owner', 'children')->whereNull('parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function getApiThreadedReplies()
    {
        return $this->threadedReplies()->jsonPaginate(10);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new TweetReceivedNewReply($reply));

        return $reply;
    }

    public function showTweet()
    {
        return static::where('id', $this->id)->first();
    }

    public function getRepliesCountAttribute()
    {
        return $this->replies()->count();
    }

    public function toSearchableArray()
    {
        return $this->toArray() + ['path' => $this->path()];
    }

    public function path()
    {
        return "/tweets/{$this->id}";
    }
}
