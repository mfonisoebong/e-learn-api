<?php

namespace App\Http\Resources\Courses;

use App\Models\Course;
use App\Models\Lesson;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Course */
class CourseResource extends JsonResource
{
    use UploadFiles;

    public function toArray(Request $request): array
    {
        $lessonsCount = 0;
        foreach ($this->modules as $module) {
            $lessonsCount += Lesson::where('module_id', $module->id)->count();
        }
        
        return [
            'id' => $this->id,
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
            'lessons' => $lessonsCount,
            'category' => $this->category_id ? new CategoryResource($this->whenLoaded('category')) : null,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
