<title>E-Learning AI - Tạo tài khoản</title>
@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Thêm User Mới</h1>
                <p class="text-slate-500 font-medium">Tạo tài khoản cho Giảng viên hoặc Sinh viên.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-slate-500 font-bold hover:text-blue-600 transition">&larr; Quay lại</a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Họ và tên</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition" required>
                        @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Email đăng nhập</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition" required>
                        @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Vai trò hệ thống</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="student" class="peer sr-only" checked>
                            <div class="rounded-xl border-2 border-slate-200 p-4 text-center font-bold text-slate-500 hover:bg-slate-50 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-600 transition">
                                Sinh viên
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="instructor" class="peer sr-only">
                            <div class="rounded-xl border-2 border-slate-200 p-4 text-center font-bold text-slate-500 hover:bg-slate-50 peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-600 transition">
                                Giảng viên
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="admin" class="peer sr-only">
                            <div class="rounded-xl border-2 border-slate-200 p-4 text-center font-bold text-slate-500 hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition">
                                Admin
                            </div>
                        </label>
                    </div>
                    @error('role') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Mật khẩu</label>
                        <input type="password" name="password" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition" required>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1 font-bold md:col-span-2">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 text-white font-black hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-1 mt-4">
                    TẠO TÀI KHOẢN
                </button>
            </div>
        </form>
    </div>
@endsection