<?php

namespace App\Traits;

use App\Notifications\YouWereFollowed;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Model;

trait Followable
{
    /**
     * @param User $user
     * @return Model|int
     * @throws Exception
     */
    public function toggleFollow(user $user)
    {
        cache()->forget('friends');
        if ($this->following($user)) {
            return $this->unfollow($user);
        }

        return $this->follow($user);
    }

    public function following(User $user)
    {
        return (bool)$this->follows()
            ->where('following_user_id', $user instanceof User ? $user->id : $user)
            ->exists();
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id')
            ->withTimestamps();
    }

    public function unfollow(User $user)
    {
        return $this->follows()->detach($user);
    }

    public function follow(User $user)
    {
        $user->notify(new YouWereFollowed(current_user()));

        return $this->follows()->save($user);
    }

    public function getFollowsCountAttribute()
    {
        return $this->follows()->count();
    }

    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'user_id')
            ->withTimestamps();
    }
}
