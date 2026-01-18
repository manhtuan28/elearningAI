<title>E-Learning AI - Quản lý tài khoản</title>
@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Quản lý Tài khoản</h1>
        <p class="text-slate-500 font-medium">Danh sách giảng viên, sinh viên và quản trị viên.</p>
    </div>

    <div class="flex gap-3">
        <div class="relative hidden md:block">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <input type="hidden" name="role" value="{{ request('role') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên hoặc email..." class="pl-10 pr-4 py-3 rounded-full border-none bg-slate-100 text-sm font-bold text-slate-800 placeholder-slate-400 shadow-sm focus:ring-2 focus:ring-blue-500 focus:bg-white w-72 transition-all">
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>

        <a href="{{ route('admin.users.import') }}" class="flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-full font-bold shadow-lg shadow-green-200 hover:bg-green-700 hover:-translate-y-1 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
            </svg>
            Nhập Excel
        </a>

        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-full font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Thêm User
        </a>
    </div>
</div>

<div class="flex space-x-1 bg-slate-100 p-1 rounded-xl w-fit mb-6">
    @php
    $tabs = [
    'all' => 'Tất cả',
    'admin' => 'Admin',
    'instructor' => 'Giảng viên',
    'student' => 'Sinh viên'
    ];
    $currentRole = request('role', 'all');
    @endphp

    @foreach($tabs as $key => $label)
    <a href="{{ route('admin.users.index', ['role' => $key]) }}"
        class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $currentRole == $key ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400">Người dùng</th>
                    <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400">Vai trò</th>
                    <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400">Ngày tham gia</th>
                    <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="group hover:bg-blue-50/30 transition duration-300">
                    <td class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black text-sm">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $user->name }}</h4>
                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-6">
                        @if($user->role === 'admin')
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-blue-100 text-blue-600 tracking-wider">Admin</span>
                        @elseif($user->role === 'instructor')
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-purple-100 text-purple-600 tracking-wider">Giảng viên</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-600 tracking-wider">Sinh viên</span>
                        @endif
                    </td>
                    <td class="p-6 text-sm font-bold text-slate-500">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="p-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="group/btn flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition-colors hover:bg-blue-600 hover:text-white" title="Sửa">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </a>

                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="group/btn flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-500 hover:text-white" title="Xóa">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center text-slate-400">Không tìm thấy người dùng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $users->appends(['role' => request('role')])->links() }}</div>
</div>
@endsection