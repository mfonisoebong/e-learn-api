<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('sign-in', 'Auth\AuthController@signIn');
        Route::post('sign-up', 'Auth\AuthController@signup');
        Route::post('send-password-reset', 'Auth\AuthController@sendPasswordReset')
            ->middleware('throttle:1,2');
        Route::post('reset-password', 'Auth\AuthController@resetPassword');
        Route::post('verify-email', 'Auth\AuthController@verifyEmail');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', 'Auth\AuthController@user');
            Route::post('logout', 'Auth\AuthController@logout');
        });
    });

    Route::apiResource('categories', 'Courses\CategoriesController');

});
