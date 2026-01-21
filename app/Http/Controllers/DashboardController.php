<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = Auth::id();
        
        $activeCourses = Course::where('status', 'open')
            ->where(function($query) use ($user) {
                $query->where('classroom_id', $user->classroom_id)
                      ->orWhereNull('classroom_id'); 
            })
            ->with(['user', 'classroom'])
            ->latest()
            ->get();

        $aiAnalysis = DB::table('student_predictions')
            ->where('user_id', $userId)
            ->first();

        return view('dashboard', compact('activeCourses', 'aiAnalysis'));
    }
}