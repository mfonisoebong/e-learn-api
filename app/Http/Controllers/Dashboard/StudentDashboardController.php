<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Traits\HttpResponses;
use App\Traits\Pagination;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    use HttpResponses, Pagination;

    public function overview(Request $request)
    {
//       TODO: Fix metric
        $user = $request->user();
        $totalEnrolledCourses = $user->enrollments()->count();
        $totalProgress = $user->enrollments()->sum('progress');
        $avgProgress = $totalEnrolledCourses > 0 ? $totalProgress / $totalEnrolledCourses : 0;
        $completedCourses = $user->enrollments()->where('progress', '>=', 100)->count();
        $inProgress = $user->enrollments()->where('progress', '<', 100)->count();

        $data = [
            'learning_progress' => [
                'avg_progress' => $avgProgress,
            ],
            'learning_accomplishments' => [
                'completed' => $completedCourses,
                'in_progress' => $inProgress,
                'certificates' => $completedCourses
            ]
        ];

        return $this->success($data, 'Overview retrieved successfully');
    }

    public function myCoursesOverview(Request $request)
    {
        $enrolledCourses = $request->user()->enrollments()
            ->latest()->take(3)
            ->get()
            ->map(fn($enrollment) => $enrollment->course);
        $courses = CourseResource::collection($enrolledCourses);

        return $this->success($courses, 'Overview retrieved successfully');
    }

    public function recentActivities(Request $request)
    {
        $notifications = Notification::where('notifiable_id', $request->user()->id)->paginate(10);
        $list = NotificationResource::collection($notifications);
        $data = $this->paginatedData($notifications, $list);

        return $this->success($data);
    }
}
