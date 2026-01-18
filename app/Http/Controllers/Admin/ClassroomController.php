<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Major;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Classroom::with('major')->withCount('students');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('major_id') && $request->major_id != 'all') {
            $query->where('major_id', $request->major_id);
        }

        $classrooms = $query->latest()->paginate(10);

        $majors = \App\Models\Major::all();

        return view('admin.classrooms.index', compact('classrooms', 'majors'));
    }

    public function create()
    {
        $majors = Major::all();
        return view('admin.classrooms.create', compact('majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:classrooms,code|max:50',
            'name' => 'required|max:255',
            'major_id' => 'required|exists:majors,id',
        ], [
            'code.required' => 'Mã lớp là bắt buộc.',
            'code.unique' => 'Mã lớp này đã tồn tại.',
            'major_id.required' => 'Vui lòng chọn chuyên ngành.',
        ]);

        Classroom::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'major_id' => $request->major_id
        ]);

        return redirect()->route('admin.classrooms.index')->with('success', 'Thêm lớp học thành công!');
    }

    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        $majors = Major::all();
        return view('admin.classrooms.edit', compact('classroom', 'majors'));
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $request->validate([
            'code' => 'required|max:50|unique:classrooms,code,' . $id,
            'name' => 'required|max:255',
            'major_id' => 'required|exists:majors,id',
        ], [
            'code.unique' => 'Mã lớp này đã được sử dụng.',
        ]);

        $classroom->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'major_id' => $request->major_id
        ]);

        return redirect()->route('admin.classrooms.index')->with('success', 'Cập nhật lớp học thành công!');
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);

        $classroom->delete();

        return redirect()->route('admin.classrooms.index')->with('success', 'Đã xóa lớp học thành công!');
    }

    public function show($id)
    {
        $classroom = Classroom::with('students')->findOrFail($id);

        $freeStudents = \App\Models\User::where('role', 'student')
            ->whereNull('classroom_id')
            ->orderBy('name')
            ->get();

        return view('admin.classrooms.show', compact('classroom', 'freeStudents'));
    }

    public function addStudent(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id'
        ]);

        $student = \App\Models\User::findOrFail($request->student_id);

        $student->update(['classroom_id' => $id]);

        return redirect()->back()->with('success', 'Đã thêm sinh viên vào lớp thành công!');
    }

    public function removeStudent($id, $student_id)
    {
        $student = \App\Models\User::where('id', $student_id)->where('classroom_id', $id)->firstOrFail();

        $student->update(['classroom_id' => null]);

        return redirect()->back()->with('success', 'Đã xóa sinh viên khỏi lớp.');
    }
    public function importStudents(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt|max:5000',
        ]);

        try {
            $import = new \App\Imports\StudentsToClassImport($id);

            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            $message = "Đã xử lý xong! Thêm thành công: " . $import->count . " sinh viên.";

            if (count($import->failed) > 0) {
                $failedList = implode(', ', array_slice($import->failed, 0, 5));
                $message .= " Có " . count($import->failed) . " email không tìm thấy tài khoản (VD: $failedList ...).";

                return redirect()->back()->with('warning', $message);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
}
