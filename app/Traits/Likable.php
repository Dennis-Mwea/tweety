<?php

namespace App\Traits;

use App\Like;
use App\Notifications\TweetWasDisliked;
use App\Notifications\TweetWasLiked;
use App\User;

trait Likable
{
    protected static function bootLikable()
    {
        static::deleting(function ($model) {
            $model->likes->each->delete();
        });
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->where('liked', true)->count();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }

    public function dislike(User $user = null)
    {
        if ($this->isDisliked()) {
            return $this->removeLike($user);
        }

        return $this->like($user, false);
    }

    public function isDisliked()
    {
        return (bool)$this->likes
            ->where('tweet_id', $this->id)
            ->where('liked', false)
            ->count();
    }

    public function removeLike($user = null)
    {
        return $this->likes()->delete($user, null);
    }

    public function like(User $user = null, $liked = true)
    {
        if ($this->isLiked() && $liked) {
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

    public function isLiked()
    {
        return (bool)$this->likes
            ->where('tweet_id', $this->id)
            ->where('liked', true)
            ->count();
    }

    public function getDislikesCountAttribute()
    {
        return $this->likes->where('liked', false)->count();
    }

    public function getIsDislikedAttribute()
    {
        return $this->isDisliked();;
    }

    public function getIsLikedAttribute()
    {
        return $this->isLiked();
    }
}
