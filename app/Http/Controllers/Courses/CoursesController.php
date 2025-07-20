<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\CourseResource;
use App\Models\Course;
use App\Traits\HttpResponses;
use App\Traits\Pagination;

class CoursesController extends Controller
{
    use HttpResponses, Pagination;

    public function discover()
    {
        $courses = Course::inRandomOrder()->paginate(10);
        $list = CourseResource::collection($courses);
        $data = $this->paginatedData($courses, $list);

        return $this->success($data);
    }
}
