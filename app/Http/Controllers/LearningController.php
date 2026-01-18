<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonSubmission;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function show($id, $lessonId = null)
    {
        $course = Course::with(['chapters.lessons' => function ($q) {
            $q->orderBy('sort_order');
        }])->findOrFail($id);

        if ($lessonId) {
            $activeLesson = Lesson::whereIn('chapter_id', $course->chapters->pluck('id'))
                ->findOrFail($lessonId);
        } else {
            $firstChapter = $course->chapters->sortBy('sort_order')->first();
            $activeLesson = $firstChapter ? $firstChapter->lessons->sortBy('sort_order')->first() : null;
        }

        $submission = null;
        if ($activeLesson) {
            $submission = LessonSubmission::where('user_id', auth()->id())
                ->where('lesson_id', $activeLesson->id)
                ->with('histories')
                ->first();
        }

        return view('learning.course', compact('course', 'activeLesson', 'submission'));
    }

    public function detail($id)
    {
        $course = Course::with(['user', 'classroom', 'chapters.lessons'])
            ->findOrFail($id);

        return view('learning.detail', compact('course'));
    }

    public function submitLesson(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $userId = Auth::id();

        $oldSubmission = LessonSubmission::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();

        $attempts = $oldSubmission ? ($oldSubmission->attempt_count + 1) : 1;

        $dataToSave = [
            'user_id' => $userId,
            'lesson_id' => $lessonId,
            'status' => 'pending',
            'attempt_count' => $attempts,
        ];

        if ($lesson->type === 'homework') {
            $request->validate([
                'file_upload' => 'required|file|mimes:doc,docx,pdf,zip,rar,png,jpg,jpeg,txt,html,css,js,bin,php,sql,zip|max:10240',
            ]);

            if ($request->hasFile('file_upload')) {
                if ($oldSubmission && isset(json_decode($oldSubmission->submission_content)->file_path)) {
                    \Storage::disk('public')->delete(json_decode($oldSubmission->submission_content)->file_path);
                }

                $file = $request->file('file_upload');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('submissions', $filename, 'public');

                $dataToSave['submission_content'] = json_encode([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'submitted_at' => now()->toDateTimeString()
                ]);
                $dataToSave['status'] = 'pending';
                $dataToSave['score'] = null;
            }
        }

        if ($lesson->type === 'quiz') {
            $submissionContent = $request->submission_content;
            $quizData = json_decode($lesson->content, true);
            $score = 0;

            if (is_array($quizData)) {
                $totalQuestions = count($quizData);
                $correctCount = 0;

                foreach ($quizData as $index => $question) {
                    if (isset($submissionContent[$index]) && $submissionContent[$index] == $question['correct']) {
                        $correctCount++;
                    }
                }
                $score = ($totalQuestions > 0) ? round(($correctCount / $totalQuestions) * 10, 2) : 0;
            }

            $dataToSave['submission_content'] = json_encode($submissionContent);
            $dataToSave['score'] = $score;
            $dataToSave['status'] = 'completed';
        }

        $submission = LessonSubmission::updateOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId],
            $dataToSave
        );

        if ($lesson->type === 'quiz') {
            \App\Models\LessonSubmissionHistory::create([
                'lesson_submission_id' => $submission->id,
                'attempt_number' => $attempts,
                'score' => $score,
                'submission_content' => json_encode($request->submission_content),
                'submitted_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'score' => $dataToSave['score'] ?? null,
            'attempts' => $attempts,
            'histories' => $submission->histories()->get()->map(function ($h) {
                return [
                    'attempt' => $h->attempt_number,
                    'score' => $h->score,
                    'time' => $h->submitted_at->diffForHumans()
                ];
            })
        ]);
    }
}
