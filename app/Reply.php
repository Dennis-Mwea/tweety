<?php

namespace App;

use App\Http\Resources\ReplyCollection;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class Reply extends Model
{
    protected $guarded = [];

    protected $appends = ['path', 'children_count'];

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
        return $this->tweet->path() . "#reply-{$this->id}";
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
