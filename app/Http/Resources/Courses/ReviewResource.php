<?php

namespace App\Http\Resources\Courses;

use App\Models\Review;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Review */
class ReviewResource extends JsonResource
{
    use UploadFiles;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'review' => $this->review,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'user' => [
                'id' => $this->user_id,
                'full_name' => $this->user->full_name,
                'avatar' => $this->getFilePath($this->user->avatar),
            ],
        ];
    }
}
