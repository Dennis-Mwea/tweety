<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    /**
     * @throws ValidationException
     */
    public function login()
    {
        $attributes = request()->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $attributes['email'])->first();

        if (!$user || !Hash::check($attributes['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        $token = $user->createToken($attributes['email'])->plaintext;

        return $this->sendresponse([
            'token' => $token,
            'user_id' => $user->id,
            'username' => $user->username
        ]);
    }

    public function register()
    {
        $validator = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);


        $user = User::create([
            'name' => $validator['name'],
            'username' => $validator['username'],
            'email' => $validator['email'],
            'password' => Hash::make($validator['password']),
        ]);

        $token = $user->createToken(request('email'))->plainTextToken;

        return $this->sendResponse([
            'token' => $token,
            'user_id' => $user->id,
            'username' => $user->username
        ], 201);
    }

    public function logout()
    {
        current_user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'User logged out!');
    }
}
