<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role === 'teacher';
    }

    public function view(User $user, Course $course): bool
    {
        $isInstructor = (int) $user->id === (int) $course->user_id;

        if ($course->status !== 'published' && !$isInstructor) {
            return false;
        }

        $isEnrolled = $course->enrollments()->where('user_id', $user->id)->exists();



        return $isInstructor || $isEnrolled;
    }

    public function create(User $user): bool
    {
        return $user->role === 'teacher';
    }

    public function update(User $user, Course $course): bool
    {
        return (int) $user->id === (int) $course->user_id;
    }

    public function delete(User $user, Course $course): bool
    {
        return (int) $user->id === (int) $course->user_id;

    }

    public function restore(User $user, Course $course): bool
    {
        return (int) $user->id === (int) $course->user_id;

    }

    public function forceDelete(User $user, Course $course): bool
    {
        return (int) $user->id === (int) $course->user_id;

    }
}
