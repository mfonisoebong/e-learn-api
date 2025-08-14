<?php

namespace App\Http\Controllers\Courses;

use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\Courses\ModuleDisplayResource;
use App\Models\Course;
use App\Notifications\Courses\EnrollmentCompleted;
use App\Traits\HttpResponses;
use App\Traits\Pagination;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CoursesController extends Controller
{
    use HttpResponses, Pagination, UploadFiles;

    public function discover()
    {
        $courses = Course::latest()->filter()->paginate(10);
        $list = CourseResource::collection($courses);
        $data = $this->paginatedData($courses, $list);

        return $this->success($data);
    }

    public function show(Course $course)
    {

//        if (!Gate::allows('view', [Course::class, $course])) {
//            return $this->failed(null, StatusCode::BadRequest->value, 'You have to enroll in this course to view it');
//        }
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

    public function store(Request $request)
    {
        Gate::authorize('create', Course::class);

        $user = $request->user();
        $data = $request->validate([
            'title' => ['required', 'string'],
            'featured_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            'description' => ['required', 'string'],
            'difficulty_level' => ['required', 'string', 'in:beginner,intermediate,advanced'],
            'category_id' => ['required', 'exists:categories,id'],
            'requirements' => ['array'],
            'requirements.*' => ['required', 'string'],
            'learning_objectives' => ['array'],
            'learning_objectives.*' => ['required', 'string']
        ]);

        $featuredImage = $this->uploadFile($request->file('featured_image'), 'courses/featured_images');

        $course = $user->courses()->create([
            ...$data,
            'featured_image' => $featuredImage
        ]);
        $course['featured_image'] = $this->getFilePath($featuredImage);

        return $this->success($course, 'Course created successfully', StatusCode::Continue->value);
    }

    public function updateProgress(Request $request, Course $course)
    {
        $request->validate([
            'lessons_completed' => ['required', 'integer'],
        ]);

        $enrollment = $request->user()->enrollments()->where('course_id', $course->id)->first();
        if (!$enrollment) {
            return $this->failed(null, StatusCode::BadRequest->value, 'You are not enrolled in this course');
        }

        if ($enrollment->is_completed) {
            return $this->failed(null, StatusCode::BadRequest->value, 'You have already completed this course');
        }

        $totalLessons = $course->lessons_count;

        if ($totalLessons < $request->lessons_completed) {
            return $this->failed(null, StatusCode::BadRequest->value, 'You have completed lessons than the total lessons');
        }

        $division = $totalLessons ? $request->lessons_completed / $totalLessons : 0;
        $percent = $division * 100;

        $enrollment->update([
            'progress' => $percent,
            'completed_lessons' => $request->lessons_completed,
            'points' => $request->lessons_completed > $enrollment->completed_lessons ?
                (float)$enrollment->points + 20 :
                (float)$enrollment->points,
        ]);

        if ($percent >= 100) {
            $request->user()->increment('points', 20);
            $request->user()->notify(new EnrollmentCompleted($enrollment));
        }

        return $this->success([
            'progress' => number_format($percent, 1) . '%',
            'completed_lessons' => $request->lessons_completed,
        ], 'Progress updated successfully');
    }

    public function update(Request $request, Course $course)
    {
        Gate::authorize('update', $course);

        $data = $request->validate([
            'title' => ['sometimes', 'string'],
            'slug' => ['required', 'string', 'max:255', 'unique:courses,slug,' . $course->id],
            'featured_image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            'description' => ['sometimes', 'string'],
            'difficulty_level' => ['sometimes', 'string', 'in:beginner,intermediate,advanced'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'requirements' => ['sometimes', 'array'],
            'requirements.*' => ['required', 'string'],
            'learning_objectives' => ['sometimes', 'array'],
            'learning_objectives.*' => ['required', 'string']
        ]);

        $featuredImage = $request->file('featured_image') ?
            $this->uploadFile($request->file('featured_image'), 'courses/featured_images')
            : $course->featured_image;


        $course->update([
            ...$data,
            'featured_image' => $featuredImage
        ]);
        $course['featured_image'] = $this->getFilePath($featuredImage);

        return $this->success($course, 'Course updated successfully');
    }

    public function destroy(Course $course)
    {
        Gate::authorize('delete', $course);

        $course->delete();

        return $this->success(null, 'Course deleted successfully');
    }

    public function restore($courseId)
    {
        $course = Course::withTrashed()->findOrFail($courseId);

        Gate::authorize('restore', $course);

        if (!$course->trashed()) {
            return $this->failed(null, StatusCode::BadRequest->value, 'Course is not deleted');
        }

        $course->restore();

        return $this->success(new CourseResource($course), 'Course restored successfully');
    }

    public function viewTeacherCourses(Request $request)
    {
        Gate::authorize('viewAny', Course::class);

        $courses = $request->user()->courses()->filter()->paginate(10);
        $list = CourseResource::collection($courses);
        $data = $this->paginatedData($courses, $list);

        return $this->success($data);
    }

    public function enroll(Course $course, Request $request)
    {
        $prevEnrollment = $request->user()->enrollments()->where('course_id', $course->id)->first();

        if ($prevEnrollment) {
            return $this->failed(null, StatusCode::BadRequest->value, 'You are already enrolled in this course');
        }

        $request->user()->enrollments()->create([
            'course_id' => $course->id,
            'progress' => 0
        ]);

        return $this->success(null, 'Course enrolled successfully');
    }

    public function forceDelete($courseId)
    {
        // Find the course including soft deleted ones
        $course = Course::withTrashed()->findOrFail($courseId);

        Gate::authorize('forceDelete', $course);

        $course->forceDelete();

        return $this->success(null, 'Course permanently deleted');
    }
}
