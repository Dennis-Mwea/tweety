<?php

namespace App\Traits;

use App\User;

trait Followable
{
    public function follow(User $user)
    {
        return $this->follows()->save($user);
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }

    public function unfollow(User $user)
    {
        return $this->follows()->detach($user);
    }

    public function toggleFollow(user $user)
    {
        return $this->follows()->toggle($user);
    }

    public function following(User $user)
    {
        return (bool)$this->follows()->where('following_user_id', $user->id)->exists();
    }
}
