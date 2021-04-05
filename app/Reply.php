<?php

namespace App;

use App\Http\Resources\ReplyCollection;
use App\Traits\Likable;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class Reply extends Model
{
    use Likable;

    protected $guarded = [];

    protected $appends = ['path', 'children_count', 'is_liked', 'is_disliked', 'likes_count', 'dislikes_count'];

    protected $with = ['owner'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($reply) {
            $reply->children->each->delete();
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }

    public function newCollection(array $models = [])
    {
        return new ReplyCollection($models);
    }

    public function parent()
    {
        return $this->belongsTo(Reply::class, 'parent_id');
    }

    public function getPathAttribute()
    {
        return $this->path();
    }

    public function path()
    {
        return $this->tweet->path() . "/replies/{$this->id}";
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
            '<a href="$0" class="text-blue-500 hover:underline" target="blank">$0</a>'
        ];

        $this->attributes['body'] = preg_replace(
            $find,
            $replace,
            $body
        );
    }

    public function allChildrenReplies()
    {
        return $this->childrenReplies()->with('allChildrenReplies');
    }

    public function scopeChildless($query)
    {
        $query->has('childrenReplies', '=', 0);
    }

    public function getChildrenCountAttribute()
    {
        return $this->children()->count();
    }

    public function children()
    {
        return $this->hasMany(Reply::class, 'parent_id', 'id')->with('owner');
    }
}
