<?php

namespace App\Http\Controllers\Courses;

use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\LessonResource;
use App\Http\Resources\Courses\ModuleDisplayResource;
use App\Models\Module;
use App\Traits\HttpResponses;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ModulesController extends Controller
{
    use HttpResponses, Pagination;

    public function store(Request $request)
    {
        Gate::authorize('create', Module::class);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $module = Module::create($data);
        return $this->success(new ModuleDisplayResource($module), 'Module created', StatusCode::Continue->value);
    }

    public function update(Request $request, Module $module)
    {
        Gate::authorize('update', $module);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $module->update($data);
        return $this->success(new ModuleDisplayResource($module), 'Module updated', StatusCode::Continue->value);
    }

    public function viewLessons(Module $module)
    {
        $lessons = $module->lessons()->paginate(10);
        $list = LessonResource::collection($lessons);
        $data = $this->paginatedData($lessons, $list);
        
        return $this->success($data);
    }

    public function destroy(Module $module)
    {
        Gate::authorize('delete', $module);
        $module->delete();
        return $this->success(null, 'Module deleted');
    }

    public function restore($moduleId)
    {
        $module = Module::withTrashed()->findOrFail($moduleId);

        Gate::authorize('restore', $module);

        if (!$module->trashed()) {
            return $this->failed(null, StatusCode::BadRequest->value, 'Module is not deleted');
        }

        $module->restore();

        return $this->success(null, 'Module restored successfully');
    }
}
