<?php

namespace App\Http\Controllers\Courses;

use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Traits\HttpResponses;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LessonsController extends Controller
{
    use UploadFiles, HttpResponses, UploadFiles;

    public function store(Request $request)
    {
        Gate::authorize('create', Lesson::class);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_in_minutes' => ['required', 'integer'],
            'document' => ['nullable', 'file', 'mimes:pdf,doc,docx,pptx,ppt,mp3,rar,zip,xlsx,xls'],
            'module_id' => ['required', 'exists:modules,id'],
            'video' => ['nullable', 'file', 'mimes:mp4,avi,mov'],
            'description' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);
        $document = $request->file('document') ?
            $this->uploadFile($request->file('document'), 'lessons/documents') : null;
        $video = $request->file('video') ?
            $this->uploadFile($request->file('video'), 'lessons/videos') : null;

        Lesson::create([
            ...$data,
            'document' => $document,
            'video' => $video,
        ]);

        return $this->success(null, 'Lesson created successfully');
    }

    public function show(Lesson $lesson)
    {
        if (!Gate::allows('view', [Lesson::class, $lesson])) {
            return $this->failed(null, StatusCode::BadRequest->value, 'You have to enroll in this course to view this lesson');
        }

        $prevLesson = Lesson::where('module_id', $lesson->module_id)
            ->where('id', '<', $lesson->id)
            ->orderBy('id', 'desc')->first();
        $nextLesson = Lesson::where('module_id', $lesson->module_id)
            ->where('id', '>', $lesson->id)
            ->orderBy('id')->first();
        $data = [
            'id' => (string)$lesson->id,
            'title' => $lesson->title,
            'description' => strlen($lesson->description) > 45 ? substr($lesson->description, 0,
                    45) . '...' : $lesson->description,
            'duration_in_minutes' => formatDuration((int)$lesson->duration_in_minutes),
            'updated_at' => $lesson->updated_at->format('Y-m-d'),
            'document_type' => $lesson->document_type,
            'video' => $lesson->video ? $this->getFilePath($lesson->video) : null,
            'document' => $lesson->document ? $this->getFilePath($lesson->document) : null,
            'content' => $lesson->content,
            'prev_lesson_id' => $prevLesson?->id,
            'next_lesson_id' => $nextLesson?->id,
        ];

        return $this->success($data, 'Lesson retrieved successfully');
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

    public function restore($lessonId)
    {
        $lesson = Lesson::withTrashed()->findOrFail($lessonId);

        Gate::authorize('restore', $lesson);

        if (!$lesson->trashed()) {
            return $this->failed(null, StatusCode::BadRequest->value, 'Lesson is not deleted');
        }

        $lesson->restore();

        return $this->success(null, 'Lesson restored successfully');
    }

}
