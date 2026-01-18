<title>E-Learning AI - Chỉnh sửa tài khoản</title>
@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Cập nhật Tài khoản</h1>
                <p class="text-slate-500 font-medium">Chỉnh sửa thông tin thành viên <span class="text-blue-600 font-bold">{{ $user->name }}</span>.</p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 font-bold hover:bg-white hover:text-blue-600 transition">
                    &larr; Quay lại
                </a>

                @if(auth()->id() !== $user->id)
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Cảnh báo: Hành động này không thể hoàn tác!\nBạn có chắc chắn muốn xóa người dùng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-50 text-red-600 font-bold hover:bg-red-600 hover:text-white transition">
                            Xóa User
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH') <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Họ và tên</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition" required>
                        @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Email đăng nhập</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition" required>
                        @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Vai trò hệ thống</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="role" value="student" class="peer sr-only" {{ old('role', $user->role) == 'student' ? 'checked' : '' }}>
                            <div class="rounded-xl border-2 border-slate-200 p-4 text-center font-bold text-slate-500 hover:bg-slate-50 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-600 transition group-hover:-translate-y-1">
                                Sinh viên
                            </div>
                        </label>
                        
                        <label class="cursor-pointer group">
                            <input type="radio" name="role" value="instructor" class="peer sr-only" {{ old('role', $user->role) == 'instructor' ? 'checked' : '' }}>
                            <div class="rounded-xl border-2 border-slate-200 p-4 text-center font-bold text-slate-500 hover:bg-slate-50 peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-600 transition group-hover:-translate-y-1">
                                Giảng viên
                            </div>
                        </label>
                        
                        <label class="cursor-pointer group">
                            <input type="radio" name="role" value="admin" class="peer sr-only" {{ old('role', $user->role) == 'admin' ? 'checked' : '' }}>
                            <div class="rounded-xl border-2 border-slate-200 p-4 text-center font-bold text-slate-500 hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition group-hover:-translate-y-1">
                                Admin
                            </div>
                        </label>
                    </div>
                    @error('role') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <h3 class="font-black text-slate-800 uppercase text-sm">Đổi mật khẩu</h3>
                        <span class="text-xs text-slate-400 font-bold bg-slate-100 px-2 py-1 rounded">(Bỏ trống nếu không muốn đổi)</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Mật khẩu mới</label>
                            <input type="password" name="password" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Nhập lại mật khẩu</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition" placeholder="••••••••">
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1 font-bold md:col-span-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 text-white font-black hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-1 mt-4">
                    LƯU THAY ĐỔI
                </button>
            </div>
        </form>
    </div>
@endsection