<?php

namespace App\Http\Resources\Courses;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Lesson */
class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'description' => strlen($this->description) > 45 ? substr($this->description, 0,
                    45) . '...' : $this->description,
            'duration_in_minutes' => formatDuration((int)$this->duration_in_minutes),
            'updated_at' => $this->updated_at->format('Y-m-d'),
            'document_type' => $this->document_type
        ];
    }
}
