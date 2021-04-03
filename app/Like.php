<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $guarded = [];

    protected $appends = ['is_liked', 'is_disliked'];

    public function getIsDislikedAttribute()
    {
        return (bool)current_user()->likes
            ->where('tweet_id', $this->id)
            ->where('liked', false)
            ->count();
    }

    public function getIsLikedAttribute()
    {
        return (bool)current_user()->likes
            ->where('tweet_id', $this->id)
            ->where('liked', true)
            ->count();
    }
}
