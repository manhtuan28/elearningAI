<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('user_id', auth()->id())
            ->with(['classroom' => function($q) {
                $q->withCount('students');
            }])
            ->latest();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->paginate(10);

        return view('instructor.courses.index', compact('courses'));
    }

    public function students($id)
    {
        $course = Course::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['classroom.students'])
            ->firstOrFail();

        $students = $course->classroom ? $course->classroom->students : collect([]);

        return view('instructor.courses.students', compact('course', 'students'));
    }
}