<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('sign-in', 'Auth\AuthController@signIn');
        Route::post('sign-up', 'Auth\AuthController@signup');
        Route::post('send-password-reset', 'Auth\AuthController@sendPasswordReset');
        Route::post('reset-password', 'Auth\AuthController@resetPassword');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', 'Auth\AuthController@user');
        });
    });

    Route::apiResource('categories', 'Courses\CategoriesController');

});
