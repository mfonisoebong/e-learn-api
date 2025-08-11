<?php

namespace App\Http\Resources\Courses;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Enrollment */
class EnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'progress' => $this->progress,
            'course' => new CourseResource($this->course),
        ];
    }
}
