<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $myCourses = Course::where('user_id', $userId)
            ->with(['classroom' => function($query) {
                $query->withCount('students');
            }])
            ->latest()
            ->get();

        $totalCourses = $myCourses->count();

        $totalStudents = $myCourses->sum(function($course) {
            return $course->classroom ? $course->classroom->students_count : 0;
        });

        return view('instructor.dashboard', compact('myCourses', 'totalCourses', 'totalStudents'));
    }
}