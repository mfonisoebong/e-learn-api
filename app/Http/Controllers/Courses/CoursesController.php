<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\Courses\ModuleDisplayResource;
use App\Models\Course;
use App\Traits\HttpResponses;
use App\Traits\Pagination;

class CoursesController extends Controller
{
    use HttpResponses, Pagination;

    public function discover()
    {
        $courses = Course::latest()->filter()->paginate(10);
        $list = CourseResource::collection($courses);
        $data = $this->paginatedData($courses, $list);

        return $this->success($data);
    }

    public function show(Course $course)
    {
        $data = new CourseResource($course);
        return $this->success($data);
    }

    public function modules(Course $course)
    {
        $modules = $course->modules()->paginate(10);
        $list = ModuleDisplayResource::collection($modules);
        $data = $this->paginatedData($modules, $list);

        return $this->success($data);
    }
}
