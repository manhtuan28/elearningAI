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
        $myCourses = Course::where('user_id', $userId)->latest()->get();

        $totalCourses = $myCourses->count();
        $totalStudents = 0;

        return view('instructor.dashboard', compact('myCourses', 'totalCourses', 'totalStudents'));
    }
}
