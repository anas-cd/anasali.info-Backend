<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\user\LoginUserRequest;
use App\Http\Requests\v1\user\RegisterUserRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use APIResponseTrait;

    /**
     * Register a new user
     */
    public function register(RegisterUserRequest $request)
    {
        // - validating request -
        $validated = $request->validated();

        // - create user -
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        /**
         * NOTE: in v1 all users who register using the API will have no prvilages since this version is only for personla use
         */
        // - providing a login token -
        return $this->success([
            'user' => UserResource::make($user),
            'token' => $user->createToken("token_$user->name", ["none"])->plainTextToken
        ], 200, 'user registered successfully!');

    }

    /**
     * Login user
     */
    public function Login(LoginUserRequest $request)
    {
        // - validating request -
        $validated = $request->validated();

        // - checking users db -
        if (!Auth::attempt($validated)) {
            // -- unauthenticated --
            return $this->failed([], 401, 'Invalid email or password');
        } else {
            // -- authenticated --

            // - retrive user info -
            $user = Auth::user();

            // - provide access token -
            return $this->success([
                'token' => $user->createToken("token_$user->name")->plainTextToken
            ], 200, "login successful!");
        }
    }
}
