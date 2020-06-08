<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Get the response for a successful password reset link.
     *
     * @param Request $request
     * @param string $response
     * @return \Illuminate\Http\Response
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return ResponseHelper::createSuccessResponse([], trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param string $response
     * @return \Illuminate\Http\Response
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return ResponseHelper::createErrorResponse(trans($response), 422);
    }
}
