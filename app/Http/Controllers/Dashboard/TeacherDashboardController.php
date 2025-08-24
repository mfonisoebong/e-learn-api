<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\CourseResource;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    use HttpResponses;


    public function overview(Request $request)
    {
        $user = $request->user();
        $courses = $user->courses()->count();
        $students = $user->courses()->withCount('enrollments')->get()->sum('enrollments_count');

        return $this->success([
            'courses' => $courses,
            'students' => $students
        ]);
    }

    public function courses(Request $request): JsonResponse
    {
        $user = $request->user();
        $courses = $user->courses()->latest()->take(5)->get();
        $list = CourseResource::collection($courses);

        return $this->success($list);
    }
}
