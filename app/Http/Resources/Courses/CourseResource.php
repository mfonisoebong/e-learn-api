<?php

namespace App\Http\Resources\Courses;

use App\Models\Course;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Course */
class CourseResource extends JsonResource
{
    use UploadFiles;

    public function toArray(Request $request): array
    {
        $course = $request->route('course');
        $duration = 0;
        $enrollments = (int)$request->user()->id === (int)$this->user_id ? [
            'enrollments' => $this->enrollments()->count()
        ] : [];
        $progress = $this->enrollments()->where('user_id', $request->user()->id)->exists() ?
            ['progress' => $this->enrollments()->where('user_id', $request->user()->id)->first()->progress] : [];


        if (!$course) {
            return [
                'id' => (string)$this->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'featured_image' => $this->getFilePath($this->featured_image),
                'description' => $this->description,
                'difficulty_level' => $this->difficulty_level,
                'requirements' => $this->requirements,
                'learning_objectives' => $this->learning_objectives,
                'category_id' => $this->category_id,
                'avg_rating' => $this->reviews()->avg('rating'),
                'instructor' => [
                    'full_name' => $this->user->full_name,
                    'email' => $this->user->email,
                    'avatar' => $this->getFilePath($this->user->avatar),
                ],
                ...($enrollments),
                ...($progress),
                'lessons' => $this->lessons_count,
                'category' => $this->category_id ? new CategoryResource($this->whenLoaded('category')) : null,
                'created_at' => $this->created_at->format('Y-m-d'),
                'updated_at' => $this->updated_at->format('Y-m-d'),
            ];
        }


        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'lessons' => $this->lessons_count,
            'featured_image' => $this->getFilePath($this->featured_image),
            'description' => $this->description,
            'difficulty_level' => $this->difficulty_level,
            'requirements' => $this->requirements,
            'learning_objectives' => $this->learning_objectives,
            'duration' => formatDuration($duration),
            'category_id' => $this->category_id,
            'avg_rating' => $this->reviews()->avg('rating'),
            ...($enrollments),
            ...($progress),
            'category' => $this->category_id ? new CategoryResource($this->whenLoaded('category')) : null,
            'created_at' => $this->created_at->format('Y-m-d'),
            'instructor' => [
                'full_name' => $this->user->full_name,
                'email' => $this->user->email,
                'avatar' => $this->getFilePath($this->user->avatar),
            ],
            'modules' => $this->modules->count(),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];

    }
}
