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
        $completedLessons = $this->enrollments()->sum('completed_lessons');
        $completedEnrollments = $this->enrollments()->where('progress', '>=', 100)->count();
        $totalEnrollments = $this->enrollments()->count();
        $completionRate = $totalEnrollments ? ($completedEnrollments / $totalEnrollments) * 100 : 0;
        return [
            'id' => (string)$this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'role' => $this->role,
            'points' => $this->points,
            'lessons_completed' => $completedLessons,
            'completion_rate' => number_format($completionRate, 1) . '%',
        ];
    }
}
