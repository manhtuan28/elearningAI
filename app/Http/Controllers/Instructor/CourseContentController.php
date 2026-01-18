<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseContentController extends Controller
{
    public function index($id)
    {
        $course = Course::where('id', $id)->where('user_id', auth()->id())
            ->with(['chapters.lessons' => function ($q) {
                $q->orderBy('sort_order');
            }])->firstOrFail();
        return view('instructor.courses.manage', compact('course'));
    }

    public function storeChapter(Request $request, $id)
    {
        $request->validate(['title' => 'required']);
        $order = Chapter::where('course_id', $id)->max('sort_order') + 1;

        Chapter::create([
            'course_id' => $id,
            'title' => $request->title,
            'sort_order' => $order
        ]);
        return back()->with('success', 'Đã thêm chương mới.');
    }

    public function updateChapter(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);
        if ($chapter->course->user_id != auth()->id()) abort(403);

        $chapter->update(['title' => $request->title]);
        return back()->with('success', 'Đã cập nhật tên chương.');
    }

    public function destroyChapter($id)
    {
        $chapter = Chapter::findOrFail($id);
        if ($chapter->course->user_id != auth()->id()) abort(403);

        foreach ($chapter->lessons as $lesson) {
            if ($lesson->file_path) Storage::disk('public')->delete($lesson->file_path);
        }

        $chapter->delete();
        return back()->with('success', 'Đã xóa chương và toàn bộ bài học.');
    }

    public function storeLesson(Request $request, $chapterId)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required|in:video,document,quiz,homework',
        ]);

        $lessonData = [
            'chapter_id' => $chapterId,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'type' => $request->type,
            'video_url' => $request->video_url,
            'duration' => $request->duration ?? 0,
            'sort_order' => Lesson::where('chapter_id', $chapterId)->max('sort_order') + 1,
        ];

        if ($request->hasFile('file_upload')) {
            $folder = ($request->type == 'video') ? 'videos' : 'documents';
            $lessonData['file_path'] = $request->file('file_upload')->store($folder, 'public');
        }

        if ($request->type == 'quiz') {
            $lessonData['content'] = json_encode($request->quiz);
        } else {
            $lessonData['content'] = ($request->type == 'document') ? $request->content_doc : $request->content_homework;
        }

        Lesson::create($lessonData);

        return response()->json(['message' => 'Đã thêm bài học thành công!']);
    }

    public function updateLesson(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'type' => 'required|in:video,document,quiz,homework',
        ]);

        $data = [
            'title' => $request->title,
            'type' => $request->type,
            'duration' => $request->duration ?? 0,
            'video_url' => $request->video_url ?? $lesson->video_url,
        ];

        if ($request->hasFile('file_upload')) {
            if ($lesson->file_path) {
                Storage::disk('public')->delete($lesson->file_path);
            }
            $folder = ($request->type == 'video') ? 'videos' : 'documents';
            $data['file_path'] = $request->file('file_upload')->store($folder, 'public');
        }

        if ($request->type == 'quiz') {
            if ($request->has('quiz')) {
                $data['content'] = json_encode($request->quiz);
            }
        } else {
            $data['content'] = ($request->type == 'document') ? $request->content_doc : $request->content_homework;
        }

        $lesson->update($data);

        return response()->json(['message' => 'Cập nhật bài học thành công!']);
    }

    public function destroyLesson($id)
    {
        $lesson = Lesson::findOrFail($id);

        if ($lesson->file_path) {
            Storage::disk('public')->delete($lesson->file_path);
        }

        $lesson->delete();
        return back()->with('success', 'Đã xóa bài học.');
    }
}