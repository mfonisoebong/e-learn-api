<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Traits\HttpResponses;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LessonsController extends Controller
{
    use UploadFiles, HttpResponses;

    public function store(Request $request)
    {
        Gate::authorize('create', Lesson::class);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_in_minutes' => ['required', 'integer'],
            'document' => ['required', 'file', 'mimes:pdf,doc,docx,pptx,ppt,mp3,rar,zip,xlsx,xls'],
            'module_id' => ['required', 'exists:modules,id'],
            'video' => ['required', 'file', 'mimes:mp4,avi,mov'],
            'description' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);
        $document = $this->uploadFile($request->file('document'), 'lessons/documents');
        $video = $this->uploadFile($request->file('video'), 'lessons/videos');

        Lesson::create([
            ...$data,
            'document' => $document,
            'video' => $video,
        ]);

        return $this->success(null, 'Lesson created successfully');
    }

    public function update(Request $request, Lesson $lesson)
    {
        Gate::authorize('update', $lesson);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_in_minutes' => ['required', 'integer'],
            'document' => ['nullable', 'file', 'mimes:pdf,doc,docx,pptx,ppt,mp3,rar,zip,xlsx,xls'],
            'module_id' => ['required', 'exists:modules,id'],
            'video' => ['nullable', 'file', 'mimes:mp4,avi,mov'],
            'description' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        if ($request->hasFile('document')) {
            $data['document'] = $this->uploadFile($request->file('document'), 'lessons/documents');
        }
        if ($request->hasFile('video')) {
            $data['video'] = $this->uploadFile($request->file('video'), 'lessons/videos');
        }

        $lesson->update($data);

        return $this->success(null, 'Lesson updated successfully');

    }

    public function destroy(Lesson $lesson)
    {
        Gate::authorize('delete', $lesson);

        $lesson->delete();

        return $this->success(null, 'Lesson deleted successfully');
    }
}
