<?php

namespace App\Http\Resources\Courses;

use App\Models\Category;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    use UploadFiles;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'featured_image' => $this->getFilePath($this->featured_image),
        ];
    }
}
