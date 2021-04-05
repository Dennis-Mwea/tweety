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
            'tweets' => $user->tweets()->paginate(10),
            'followings' => $user->follows->count(),
            'followers' => $user->followers()->count()
        ]);
    }

    public function edit(User $user)
    {
        $this->authorize('edit', $user);

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
            'banner' => ['file'],
            'description' => ['sometimes', 'string'],
        ]);

        $this->authorize('edit', $user);

        if (request('avatar'))
            $attributes['avatar'] = request()->file('avatar')->storePublicly('avatars', 'public');

        if (request('banner'))
            $attributes['banner'] = request()->file('banner')->storePublicly('banners', 'public');

        $user->update($attributes);

        return redirect($user->path())->with('flash', 'Your profile has been updated!');
    }
}
