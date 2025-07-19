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
    }

    public function update(User $user, Module $module): bool
    {
    }

    public function delete(User $user, Module $module): bool
    {
    }

    public function restore(User $user, Module $module): bool
    {
    }

    public function forceDelete(User $user, Module $module): bool
    {
    }
}
