<?php

namespace App\Traits;

use App\Like;
use App\Notifications\TweetWasDisliked;
use App\Notifications\TweetWasLiked;
use App\Reply;
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
        return $this->likes->where('liked', true)->count();
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

    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }

    public function like(User $user = null, $liked = true)
    {
        if ($this->isLiked() && $liked) {
            return $this->removeLike($user);
        }

        $this->notifyowner($this instanceof Reply ? $this->owner : $this->user, $liked);

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

    public function notifyOwner($owner, $liked)
    {
        if ($owner->isNot(current_user())) {
            $owner->notify(
                $liked
                    ? new TweetWasLiked($subject = $this, current_user())
                    : new TweetWasDisliked($subject = $this, current_user())
            );
        }
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
