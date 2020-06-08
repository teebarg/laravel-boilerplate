<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//health check port
Route::any('/health-check', function() {
    \Illuminate\Support\Facades\DB::getDefaultConnection();
    return response()->json(['data' => 'Working']);
});

Route::group([
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
//    Route::post('logout', 'AuthController@logout');
//    Route::post('refresh', 'AuthController@refresh');
//    Route::get('me', 'AuthController@me');
//    Route::post('social','AuthController@socialite');
//    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});

Route::resource('countries', 'CountryAPIController');
