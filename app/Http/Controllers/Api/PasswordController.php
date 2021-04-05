<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update()
    {
        $attributes = request()->validate([
            'old_password' => ['required', new MatchOldPassword],
            'new_password' => 'required|confirmed'
        ]);

        current_user()->update([
            'password' => Hash::make($attributes['new_password'])
        ]);

        current_user()->tokens()->delete();

        $token = current_user()->createToken(current_user()->email)->plainTextToken;

        return $this->sendResponse($token, '', 201);
    }
}
