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
            $submission = LessonSubmission::where('user_id', Auth::id())
                ->where('lesson_id', $activeLesson->id)
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
        $submissionContent = $request->submission_content;
        $score = null;

        if ($lesson->type === 'quiz') {
            $quizData = json_decode($lesson->content, true);
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
        }

        $submission = LessonSubmission::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lesson_id' => $lessonId
            ],
            [
                'submission_content' => json_encode($submissionContent),
                'score' => $score,
                'status' => ($lesson->type === 'quiz') ? 'completed' : 'pending' 
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Bài làm của bạn đã được ghi lại!',
            'score' => $score
        ]);
    }
}