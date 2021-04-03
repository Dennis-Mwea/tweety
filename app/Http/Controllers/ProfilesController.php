<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Validation\Rule;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', [
            'user' => $user,
            'tweets' => $user->tweets()->withLikes()->paginate(10)
        ]);
    }

    public function edit(User $user)
    {
        return view('profiles.edit', [
            'user' => $user
        ]);
    }

    public function update(User $user)
    {
        $attributes = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user), 'alpha_dash'],
            'avatar' => ['file'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (request('avatar'))
            $attributes['avatar'] = request('avatar')->store('avatars');

        $user->update($attributes);

        return redirect($user->path());
    }
}
