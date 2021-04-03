<?php

namespace App\Traits;

use App\Like;
use App\User;
use Illuminate\Database\Eloquent\Builder;

trait Likable
{
    public function scopeWithLikes(Builder $query)
    {
        $query->leftJoinSub(
            'select tweet_id,sum(liked) likes, sum(!liked) dislikes from likes group by tweet_id',
            'likes',
            'likes.tweet_id',
            'tweets.id'
        );
    }

    public function isDislikedBy(User $user)
    {
        return (bool)$user
            ->likes
            ->where('tweet_id', $this->id)
            ->where('liked', false)
            ->count();

    }

    public function isLikedBy(User $user)
    {
        return (bool)$user->likes
            ->where('tweet_id', $this->id)
            ->where('liked', true)
            ->count();
    }

    public function dislike(User $user = null)
    {
        return $this->like($user, false);
    }

    public function like(User $user = null, $liked = true)
    {
        return $this->likes()->updateOrCreate([
            'user_id' => $user ? $user->id : current_user()->id,
        ], [
            'liked' => $liked
        ]);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
