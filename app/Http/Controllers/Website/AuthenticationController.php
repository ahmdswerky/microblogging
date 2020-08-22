<?php

namespace App\Http\Controllers\Website;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MicrobloggingService;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\Website\LoginRequest;
use App\Http\Resources\Website\UserResource;
use App\Http\Requests\Website\RegisterRequest;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request)
    {
        $token = $request->authenticateUser();

        return response([
            'user' => new UserResource($request->user),
            'token' => $token,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $token = $request->registerUser()->authenticateUser();

        return response([
            'user' => new UserResource($request->user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user('api')->token()->revoke();

        return response([
            'message' => __('auth.logout'),
        ]);
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $social = Socialite::driver($provider)->user();

        $user = $this->getUser($social);

        if ( !$user->image ) {
            $user->addMedia($social->avatar_original, 'photo', [
                'name' => 'image',
            ]);
        }

        (new MicrobloggingService($provider))->createOrUpdateUserAccount($user, [
            'account_id' => $social->getId(),
            'username' => $social->getNickname(),
            'access_token' => $social->token,
            'access_token_secret' => $social->tokenSecret,
        ]);

        return response([
            // 'user' => new UserResource($user),
            'twitter_account' => $user->twitterAccount,
            'token' => $social->token,
            'token_secret' => $social->tokenSecret,
            'social' => $social,
            'user' => $user,
        ]);
    }

    protected function getUser($social)
    {
        if ( auth()->check() ) {
            return auth()->user();
        }

        return User::create([
            'name' => $social->getName(),
            'email' => $social->getEmail() ?? Str::random() . "@website.com",
            'password' => Str::random(40),
        ]);
    }
}
