<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::withCount('classrooms')->latest()->paginate(10);
        return view('admin.majors.index', compact('majors'));
    }

    public function create()
    {
        return view('admin.majors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:majors,name',
        ], [
            'name.required' => 'Tên chuyên ngành không được để trống.',
            'name.unique' => 'Chuyên ngành này đã tồn tại.',
        ]);

        Major::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.majors.index')->with('success', 'Thêm chuyên ngành thành công!');
    }

    public function edit($id)
    {
        $major = Major::findOrFail($id);
        return view('admin.majors.edit', compact('major'));
    }

    public function update(Request $request, $id)
    {
        $major = Major::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:majors,name,'.$id, 
        ], [
            'name.required' => 'Tên chuyên ngành không được để trống.',
            'name.unique' => 'Tên chuyên ngành này đã được sử dụng.',
        ]);

        $major->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.majors.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $major = Major::findOrFail($id);
        
        if ($major->classrooms()->count() > 0) {
            return back()->with('error', 'Không thể xóa! Đang có lớp học thuộc chuyên ngành này.');
        }

        $major->delete();

        return redirect()->route('admin.majors.index')->with('success', 'Đã xóa chuyên ngành!');
    }
}