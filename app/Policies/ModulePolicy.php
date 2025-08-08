<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Module $module): bool
    {
    }

    public function create(User $user): bool
    {
        return $user->role === 'teacher';
    }

    public function update(User $user, Module $module): bool
    {
        return (int) $user->id === (int) $module->course->user_id;
    }

    public function delete(User $user, Module $module): bool
    {
        return (int) $user->id === (int) $module->course->user_id;

    }

    public function restore(User $user, Module $module): bool
    {
        return (int) $user->id === (int) $module->course->user_id;

    }

    public function forceDelete(User $user, Module $module): bool
    {
        return (int) $user->id === (int) $module->course->user_id;

    }
}
