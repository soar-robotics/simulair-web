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

Route::group(['namespace' => 'Api', 'middleware' => ['api']], function () {
    Route::group(['middleware' => ['verified']], function() {
        Route::group(['prefix' => 'environments'], function () {
            Route::get('/', 'EnvironmentController@index');
        });
        Route::group(['prefix' => 'robots'], function () {
            Route::get('/', 'RobotController@index');
        });
        Route::group(['prefix' => 'simulations'], function () {
            Route::get('/', 'SimulationController@index');
            Route::post('/', 'SimulationController@store');
            Route::patch('/{id}/{status}', 'SimulationController@setStatus');
        });
        Route::group(['prefix' => 'me'], function () {
            Route::get('/', 'UserController@me');
            Route::patch('/', 'UserController@meUpdate');
            Route::post('image', 'UserController@meProfileImageUpdate');
            Route::get('image', 'UserController@meGetProfileImage');
        });
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/{id}/image', 'UserController@getProfileImage')->name('users.user.image');
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('token', 'AuthController@login');
        Route::post('token/invalidate', 'AuthController@invalidate');
        Route::post('token/refresh', 'AuthController@refresh');
        Route::post('register', 'AuthController@register');
        Route::group(['prefix' => 'verification'], function() {
            /*
             * TODO Add callback url from frontend to register endpoint to be able to redirect successfully.
             */
           Route::get('email/verify/{id}', 'AuthController@verify')->name('verification.verify');
           Route::post('email/resend', 'AuthController@resend');
        });

        Route::get('oauth/google', 'AuthController@redirectToProvider');
        Route::post('oauth/google/callback', 'AuthController@handleProviderCallback');
    });
});
