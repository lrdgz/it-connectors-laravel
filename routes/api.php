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



Route::group(['middleware' => ['json.response']], function () {

    /*NO IMPLEMENT AUTH ROUTES*/
    Route::namespace('Api\Auth')->name('api.')->group(function() {
        Route::post('login',    'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('refresh', 'AuthController@refresh');

        //    Route::post('recover', 'Api\Auth\AuthController@recover');
        //    Route::post('change', 'Api\Auth\AuthController@change');
        //    Route::get('verify-email/{token}', 'Api\Auth\EmailVerificationController@verify')->name('email-verify');
    });

    /*AUTHENTICATED ROUTES*/
    Route::middleware(['auth:api'])->namespace('Api')->group(function(){
        Route::post('logout', 'Auth\AuthController@logout');
    });

});


//Route::post('recover', 'Api\Auth\AuthController@recover');
//Route::post('change', 'Api\Auth\AuthController@change');
//Route::get('verify-email/{token}', 'Api\Auth\EmailVerificationController@verify')->name('email-verify');

//Route::post('login', 'Api\UserController@login');
//Route::post('register', 'Api\UserController@register');


//Route::group(['middleware' => 'auth:api'], function(){
//    Route::post('details', 'API\UserController@details');
//});
