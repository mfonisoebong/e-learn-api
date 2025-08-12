<?php

use App\Http\Middleware\EmailVerified;
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

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('verify-email', 'Auth\AuthController@verifyEmail');
            Route::post('resend-email-verification', 'Auth\AuthController@resendEmailVerification');
            Route::get('user', 'Auth\AuthController@user');
            Route::post('logout', 'Auth\AuthController@logout');
        });
    });

    Route::apiResource('categories', 'Courses\CategoriesController');
    Route::middleware(['auth:sanctum', EmailVerified::class])->group(function () {
        Route::prefix('courses')->group(function () {
            Route::get('discover', 'Courses\CoursesController@discover');
            Route::get('{course}/modules', 'Courses\CoursesController@modules');
            Route::post('', 'Courses\CoursesController@store');
            Route::get('teacher', 'Courses\CoursesController@viewTeacherCourses');
            Route::get('{course}', 'Courses\CoursesController@show');
            Route::post('{course}', 'Courses\CoursesController@update');
            Route::post('{course}/enroll', 'Courses\CoursesController@enroll');
            Route::delete('{course}', 'Courses\CoursesController@destroy');
            Route::put('{course}/restore', 'Courses\CoursesController@restore');
            Route::put('{course}/update-progress', 'Courses\CoursesController@updateProgress');
        });
        Route::prefix('modules')->group(function () {
            Route::post('', 'Courses\ModulesController@store');
            Route::patch('{module}', 'Courses\ModulesController@update');
            Route::get('{module}/lessons', 'Courses\ModulesController@viewLessons');
            Route::delete('{module}', 'Courses\ModulesController@destroy');
            Route::put('{module}/restore', 'Courses\ModulesController@restore');
        });
        Route::prefix('lessons')->group(function () {
            Route::post('', 'Courses\LessonsController@store');
            Route::get('{lesson}', 'Courses\LessonsController@show');
            Route::post('{lesson}', 'Courses\LessonsController@update');
            Route::delete('{lesson}', 'Courses\LessonsController@destroy');
            Route::put('{lesson}/restore', 'Courses\LessonsController@restore');
        });

        Route::prefix('enrollments')->group(function () {
            Route::get('courses-stats', 'Courses\EnrollmentsController@coursesStats');
            Route::get('', 'Courses\EnrollmentsController@studentsEnrollments');
        });

        Route::prefix('dashboard/student')->group(function () {
            Route::get('overview', 'Dashboard\StudentDashboardController@overview');
            Route::get('leaderboard', 'Dashboard\StudentDashboardController@leaderboard');
            Route::get('recent-activities', 'Dashboard\StudentDashboardController@recentActivities');
            Route::get('my-courses/overview', 'Dashboard\StudentDashboardController@myCoursesOverview');
        });
    });


});
