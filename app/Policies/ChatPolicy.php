<?php

namespace App\Policies;

use App\Chat;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @param User $user
     * @param Chat $chat
     * @return mixed
     */
    public function view(User $user, Chat $chat)
    {
        return $chat->participants->contains($user);
    }
}
