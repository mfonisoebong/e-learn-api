<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\EnrollmentResource;
use App\Traits\HttpResponses;
use App\Traits\Pagination;
use Illuminate\Http\Request;

class EnrollmentsController extends Controller
{
    use HttpResponses, Pagination;

    public function coursesStats(Request $request)
    {
        $all = $request->user()->enrollments()->count();
        $completed = $request->user()->enrollments()->where('progress', '>=', 100)->count();
        $pending = $request->user()->enrollments()->where('progress', '<', 100)->count();

        return $this->success(compact('all', 'completed', 'pending'));
    }

    public function studentsEnrollments(Request $request)
    {
        $enrollments = $request->user()->enrollments()->filter()->paginate(9);
        $list = EnrollmentResource::collection($enrollments);

        $data = $this->paginatedData($enrollments, $list);

        return $this->success($data);
    }
}
