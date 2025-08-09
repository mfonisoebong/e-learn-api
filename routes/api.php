<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EmailVerified;

Route::
        namespace('App\Http\Controllers')->group(function () {


            Route::get('/', 'Misc\HealthCheckController');

            Route::prefix('auth')->group(function () {
                Route::post('sign-in', 'Auth\AuthController@signIn');
                Route::post('sign-up', 'Auth\AuthController@signup');
                Route::post('send-password-reset', 'Auth\AuthController@sendPasswordReset')
                    ->middleware('throttle:1,2');
                Route::post('reset-password', 'Auth\AuthController@resetPassword');

                Route::middleware('auth:sanctum')->group(function () {
                    Route::post('verify-email', 'Auth\AuthController@verifyEmail');
                    Route::get('user', 'Auth\AuthController@user');
                    Route::post('logout', 'Auth\AuthController@logout');
                });
            });

            Route::apiResource('categories', 'Courses\CategoriesController');

            Route::prefix('courses')->group(function () {
                Route::get('discover', 'Courses\CoursesController@discover');
                Route::get('{course}', 'Courses\CoursesController@show');
                Route::get('{course}/modules', 'Courses\CoursesController@modules');
                Route::middleware(['auth:sanctum', EmailVerified::class])->group(function () {
                    Route::post('', 'Courses\CoursesController@store');
                    Route::post('{course}', 'Courses\CoursesController@update');
                    Route::delete('{course}', 'Courses\CoursesController@destroy');
                    Route::put('{course}/restore', 'Courses\CoursesController@restore');
                });
            });

            Route::prefix('modules')->group(function () {
                Route::middleware(['auth:sanctum', EmailVerified::class])->group(function () {
                    Route::post('', 'Courses\ModulesController@store');
                    Route::patch('{module}', 'Courses\ModulesController@update');
                    Route::delete('{module}', 'Courses\ModulesController@destroy');
                    Route::put('{module}/restore', 'Courses\ModulesController@restore');
                });
            });

            Route::prefix('lessons')->group(function () {
                Route::middleware(['auth:sanctum', EmailVerified::class])->group(function () {
                    Route::post('', 'Courses\LessonsController@store');
                    Route::post('{lesson}', 'Courses\LessonsController@update');
                    Route::delete('{lesson}', 'Courses\LessonsController@destroy');
                    Route::put('{lesson}/restore', 'Courses\LessonsController@restore');
                });
            });

        });
