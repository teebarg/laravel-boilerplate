<?php

namespace App\Traits;

use App\Helpers\Helper;
use App\Helpers\ResponseHelper;
use App\Helpers\ResponseMessages;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

trait SocialLoginTrait
{
    /**
     * Log in with social credentials.
     *
     * @param Request $request
     * @return UserResource|Response
     */
    public function socialLogin(Request $request)
    {
        $this->validateRequest($request);
        $driver = $request->input('driver');
        try {
            $socialUser = Socialite::driver($driver)->userFromToken($request->input('tokenId'));
            $existUser = User::where('email', $socialUser->email)->first();

            if ($existUser) {
                $token = auth()->login($existUser);
            } else {
                $user = User::create([
                    'name' => Helper::random_username($socialUser->name),
                    'email' => $socialUser->email,
                    'password' => bcrypt(rand(1, 10000))
                ]);
                $token = auth()->login($user);
            }
            JWTAuth::setToken($token);
            return new UserResource(auth()->user());
        } catch (\Exception $e) {
            return ResponseHelper::createErrorResponse($e->getMessage(), ResponseMessages::LOGIN_FAIL);
        }
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'driver' => 'required|string',
            'tokenId' => 'required|string',
        ]);
    }
}
