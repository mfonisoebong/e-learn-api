<?php

namespace App\Http\Controllers\Courses;

use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\Courses\ModuleDisplayResource;
use App\Models\Course;
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

        return $this->success($course, 'Course created successfully', StatusCode::Continue ->value);
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

    public function forceDelete($courseId)
    {
        // Find the course including soft deleted ones
        $course = Course::withTrashed()->findOrFail($courseId);

        Gate::authorize('forceDelete', $course);

        $course->forceDelete();

        return $this->success(null, 'Course permanently deleted');
    }
}
