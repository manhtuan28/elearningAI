<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersTemplateExport;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'all');
        
        $query = User::latest();

        if (in_array($role, ['admin', 'instructor', 'student'])) {
            $query->where('role', $role);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users', 'role'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,instructor,student'],
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng mới thành công!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$id],
            'role' => ['required', 'in:admin,instructor,student'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->back()->with('error', 'Bạn không thể xóa chính tài khoản đang đăng nhập!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng thành công!');
    }
    public function import()
    {
        return view('admin.users.import');
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->route('admin.users.index')->with('success', 'Nhập dữ liệu người dùng thành công!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()->with('error', 'Lỗi nhập liệu: Dòng ' . $failures[0]->row() . ' - ' . $failures[0]->errors()[0]);
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }

        dd('Đã vào hàm storeImport', $request->all(), $request->file('file')); 

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(new UsersTemplateExport, 'mau_nhap_user_chuan.xlsx');
    }
}