<?php

namespace App\Traits;

use App\Like;
use App\Notifications\TweetWasDisliked;
use App\Notifications\TweetWasLiked;
use App\User;
use Illuminate\Database\Eloquent\Builder;

trait Likable
{
    protected static function bootLikable()
    {
        static::deleting(function ($model) {
            $model->likes->each->delete();
        });
    }

    public function scopeWithLikes(Builder $query, $id = null)
    {
        $id ?
            $query->leftJoinSub(
                "select tweet_id,sum(liked) likes, sum(!liked) dislikes from likes group by tweet_id having tweet_id = {$id}",
                'likes',
                'likes.tweet_id',
                'tweets.id') :
            $query->leftJoinSub(
                "select tweet_id,sum(liked) likes, sum(!liked) dislikes from likes group by tweet_id",
                'likes',
                'likes.tweet_id',
                'tweets.id');
    }

    public function dislike(User $user = null)
    {
        if ($this->isDislikedBy($user = current_user())) {
            return $this->removeLike($user);
        }

        return $this->like($user, false);
    }

    public function isDislikedBy(User $user)
    {
        return (bool)$user
            ->likes
            ->where('tweet_id', $this->id)
            ->where('liked', false)
            ->count();

    }

    public function removeLike($user = null)
    {
        return $this->likes()->delete($user, null);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function like(User $user = null, $liked = true)
    {
        if ($this->isLikedBy($user = current_user()) && $liked) {
            return $this->removeLike($user);
        }

        $this->user->notify($liked ? new TweetWasLiked($tweet = $this, current_user())
            : new TweetWasDisliked($tweet = $this, current_user()));

        return $this->likes()->updateOrCreate([
            'user_id' => $user ? $user->id : current_user()->id,
        ], [
            'liked' => $liked
        ]);
    }

    public function isLikedBy(User $user)
    {
        return (bool)$user->likes
            ->where('tweet_id', $this->id)
            ->where('liked', true)
            ->count();
    }

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
