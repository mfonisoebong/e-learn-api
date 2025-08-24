<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Enrollment $enrollment): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        $course = $enrollment->course;
        return $course->status === 'published';
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
    }

    public function restore(User $user, Enrollment $enrollment): bool
    {
    }

    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
    }
}
