<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Lesson $lesson): bool
    {
    }

    public function create(User $user): bool
    {
        return $user->role === 'teacher';
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return (int) $user->id === (int) $lesson->module->course->user_id;
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return (int) $user->id === (int) $lesson->module->course->user_id;
    }

    public function restore(User $user, Lesson $lesson): bool
    {
        return (int) $user->id === (int) $lesson->module->course->user_id;
    }

    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return (int) $user->id === (int) $lesson->module->course->user_id;
    }
}
