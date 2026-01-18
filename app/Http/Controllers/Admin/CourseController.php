<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Major;
use App\Models\Classroom;

class CourseController extends Controller
{
    public function dashboard()
    {
        $totalCourses = Course::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();

        $totalMajors = Major::count();
        $totalClassrooms = Classroom::count();

        $recentCourses = Course::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalStudents',
            'totalInstructors',
            'totalMajors',
            'totalClassrooms',
            'recentCourses'
        ));
    }

    public function index(Request $request)
    {
        $query = Course::with(['user', 'classroom'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $courses = $query->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $instructors = User::where('role', 'instructor')->get();
        $classrooms = Classroom::all();
        return view('admin.courses.create', compact('instructors', 'classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'code' => 'required|unique:courses,code|max:50',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,open,closed',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ], [
            'title.required' => 'Tên học phần không được để trống',
            'code.required' => 'Mã học phần là bắt buộc',
            'code.unique' => 'Mã học phần này đã tồn tại',
            'user_id.required' => 'Vui lòng phân công giảng viên',
            'image.max' => 'Ảnh không được quá 2MB',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        Course::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'content' => $request->content,
            'user_id' => $request->user_id,
            'status' => $request->status,
            'image' => $imagePath,
            'classroom_id' => $request->classroom_id,
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Đã tạo học phần mới thành công!');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $instructors = User::where('role', 'instructor')->get();
        $classrooms = Classroom::all();

        return view('admin.courses.edit', compact('course', 'instructors', 'classrooms'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'code' => 'required|max:50|unique:courses,code,' . $id,
            'user_id' => 'required|exists:users,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,open,closed',
        ]);

        $data = [
            'title' => $request->title,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'content' => $request->content,
            'user_id' => $request->user_id,
            'classroom_id' => $request->classroom_id,
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('success', 'Cập nhật học phần thành công!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Đã xóa học phần thành công!');
    }
}
