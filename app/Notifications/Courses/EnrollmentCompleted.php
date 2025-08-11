<?php

namespace App\Notifications\Courses;

use App\Models\Enrollment;
use Illuminate\Notifications\Notification;

class EnrollmentCompleted extends Notification
{
    public function __construct(public Enrollment $enrollment)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'description' => $this->enrollment->course->description,
            'title' => 'Completed ' . $this->enrollment->course->title,
            'points' => 20,
        ];
    }
}
