<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UserResource;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends AppBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        if (! $token = auth()->attempt($this->credentials($request))) {
            return false;
        }
        JWTAuth::setToken($token);
        return true;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return UserResource
     */
    protected function sendLoginResponse(Request $request)
    {
        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }
        return new UserResource(auth()->user());
    }

    /**
     * Get the failed login response instance.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return ResponseHelper::createErrorResponse(trans('auth.failed'), 422);
    }
}
