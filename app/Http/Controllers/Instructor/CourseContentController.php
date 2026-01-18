<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\LessonSubmission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Imports\CourseContentImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseContentController extends Controller
{
    public function index($id)
    {
        $course = Course::where('id', $id)->where('user_id', auth()->id())
            ->with(['chapters.lessons' => function ($q) {
                $q->withCount('submissions')->orderBy('sort_order');
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
            $file = $request->file('file_upload');

            $filename = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            $lessonData['file_path'] = $file->storeAs($folder, $filename, 'public');
        }

        if ($request->type == 'quiz') {
            $lessonData['content'] = json_encode($request->quiz);
        } else {
            $lessonData['content'] = ($request->type == 'document') ? $request->content_doc : $request->content_homework;
        }

        Lesson::create($lessonData);

        return response()->json(['message' => 'Đã thêm nội dung thành công!']);
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
            if (isset($lesson) && $lesson->file_path) {
                Storage::disk('public')->delete($lesson->file_path);
            }

            $file = $request->file('file_upload');
            $folder = ($request->type == 'video') ? 'videos' : 'documents';

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $cleanName = Str::slug($originalName) . '.' . $extension;

            $fileName = time() . '-' . $cleanName;

            $path = $file->storeAs($folder, $fileName, 'public');

            if (isset($lessonData)) {
                $lessonData['file_path'] = $path;
            } else {
                $data['file_path'] = $path;
            }
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

    public function viewSubmissions($lessonId)
    {
        $lesson = Lesson::with('chapter.course')->findOrFail($lessonId);

        if ($lesson->chapter->course->user_id != auth()->id()) {
            abort(403);
        }

        $submissions = LessonSubmission::where('lesson_id', $lessonId)
            ->with('user')
            ->latest()
            ->get();

        return view('instructor.courses.submissions', compact('lesson', 'submissions'));
    }

    public function importContent(Request $request, $id)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CourseContentImport($id), $request->file('excel_file'));
            return back()->with('success', 'Đã nhập nội dung từ Excel thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi file Excel: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="mau_nhap_khoa_hoc.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['ten_chuong', 'ten_bai_hoc', 'loai_noi_dung', 'video_url', 'thoi_luong', 'noi_dung']);

            fputcsv($handle, ['Chương 1: Giới thiệu', '', '', '', '', '']);
            fputcsv($handle, ['', 'Bài 1: Cài đặt', 'video', 'https://youtu.be/xxx', '10', '']);
            fputcsv($handle, ['', 'Bài 2: Trắc nghiệm vui', 'quiz', '', '15', '1+1 bằng mấy? | 1 | *2 | 3 | 4']);

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
