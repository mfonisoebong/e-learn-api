<?php

use Illuminate\Support\Facades\Route;

Route::
        namespace('App\Http\Controllers')->group(function () {

            Route::get('/', 'Misc\HealthCheckController');

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

            Route::prefix('courses')->group(function () {
                Route::get('discover', 'Courses\CoursesController@discover');
                Route::get('{course}', 'Courses\CoursesController@show');
                Route::get('{course}/modules', 'Courses\CoursesController@modules');
            });

        });
