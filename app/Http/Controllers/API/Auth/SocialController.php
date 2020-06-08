<?php

namespace App\Http\Controllers\Auth;

use App\Traits\SocialLoginTrait;
use App\Http\Controllers\Controller;

class SocialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Social Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use SocialLoginTrait;
}
