<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;

class LearningController extends Controller
{
    public function show($id, $lessonId = null)
    {
        $course = Course::with(['chapters.lessons' => function($q) {
            $q->orderBy('sort_order');
        }])->findOrFail($id);

        if ($lessonId) {
            $activeLesson = Lesson::whereIn('chapter_id', $course->chapters->pluck('id'))
                ->findOrFail($lessonId);
        } else {
            $firstChapter = $course->chapters->sortBy('sort_order')->first();
            $activeLesson = $firstChapter ? $firstChapter->lessons->sortBy('sort_order')->first() : null;
        }

        return view('learning.course', compact('course', 'activeLesson'));
    }
}