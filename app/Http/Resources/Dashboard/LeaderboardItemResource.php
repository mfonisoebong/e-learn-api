<?php

namespace App\Http\Resources\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class LeaderboardItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $totalLessons = $this->course->lessons_count;
        $completedLessons = $this->completed_lessons;
        $completionRatePercent = $totalLessons ? ($completedLessons / $totalLessons) * 100 : 0;

        return [
            'id' => (string)$this->id,
            'full_name' => $this->user->full_name,
            'completed_lessons' => $completedLessons,
            'points' => $this->points,
            'completion_rate' => number_format($completionRatePercent, 2) . '%',
        ];
    }
}
