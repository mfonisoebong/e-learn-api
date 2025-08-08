<?php

namespace App\Http\Controllers\Courses;

use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ModulesController extends Controller
{
    use HttpResponses;

    public function store(Request $request)
    {
        Gate::authorize('create', Module::class);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $module = Module::create($data);
        return $this->success($module, 'Module created', StatusCode::Continue ->value);
    }

    public function update(Request $request, Module $module)
    {
        Gate::authorize('update', $module);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $module->update($data);
        return $this->success($module, 'Module updated', StatusCode::Continue ->value);
    }

    public function destroy(Module $module)
    {
        Gate::authorize('delete', $module);
        $module->delete();
        return $this->success(null, 'Module deleted');
    }

    public function restore(Module $module)
    {
        Gate::authorize('restore', $module);
        $module->restore();
        return $this->success($module, 'Module restored');
    }
}
