<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $activeCourses = Course::where('status', 'open')
            ->where(function($query) use ($user) {
                $query->where('classroom_id', $user->classroom_id)
                      ->orWhereNull('classroom_id'); 
            })
            ->with(['user', 'classroom'])
            ->latest()
            ->get();

        return view('dashboard', compact('activeCourses'));
    }
}