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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*CUSTOM API ROUTES*/
Route::get('verify-email/{token}', 'Api\Auth\EmailVerificationController@verify')->name('email-verify');

Route::group(['middleware' => ['json.response']], function () {

    /*NO IMPLEMENT AUTH ROUTES*/
    Route::namespace('Api\Auth')/*->name('api.')*/->group(function() {
        Route::post('login',   'AuthController@login');
        Route::post('register','AuthController@register');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('recover', 'AuthController@recover');
        Route::post('change',  'AuthController@change');
    });


    /*AUTHENTICATED ROUTES*/
    Route::middleware(['auth:api', 'CheckClientCredentials', 'verified_email', 'is_active'])->namespace('Api')->group(function(){
        Route::post('logout', 'Auth\AuthController@logout');
        Route::resource('posts', 'Posts\PostController')->except(['create', 'edit']);
        Route::resource('comments', 'Posts\CommentController')->except(['create', 'edit']);
    });

});


/*POST ROUTES*/
//Route::resource('posts', 'Api\Posts\PostController');


//Route::post('recover', 'Api\Auth\AuthController@recover');
//Route::post('change', 'Api\Auth\AuthController@change');
//Route::get('verify-email/{token}', 'Api\Auth\EmailVerificationController@verify')->name('email-verify');

//Route::post('login', 'Api\UserController@login');
//Route::post('register', 'Api\UserController@register');


//Route::group(['middleware' => 'auth:api'], function(){
//    Route::post('details', 'API\UserController@details');
//});
