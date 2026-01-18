<title>E-Learning AI - Quản lý học phần</title>
@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Quản lý Học phần</h1>
            <p class="text-slate-500 font-medium">Danh sách các môn học và lớp tín chỉ trong hệ thống.</p>
        </div>
        
        <div class="flex gap-3">
            <div class="relative hidden md:block">
                <form action="{{ route('admin.courses.index') }}" method="GET">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Tìm theo mã hoặc tên..." 
                           class="pl-10 pr-4 py-3 rounded-full border-none bg-slate-100 text-sm font-bold text-slate-800 placeholder-slate-400 shadow-sm focus:ring-2 focus:ring-blue-500 focus:bg-white w-72 transition-all">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
            </div>

            <a href="{{ route('admin.courses.create') }}" class="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-full font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Thêm mới
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400">Thông tin Học phần</th>
                        <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400">Giảng viên</th>
                        <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400">Lớp học</th>
                        <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400">Trạng thái</th>
                        <th class="p-6 text-xs font-black uppercase tracking-wider text-slate-400 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                    <tr class="group hover:bg-blue-50/30 transition duration-300">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black shadow-md group-hover:scale-110 transition-transform duration-300">
                                    {{ substr($course->title, 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-800 text-base group-hover:text-blue-600 transition-colors">{{ $course->title }}</h4>
                                        @if($course->code)
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-black border border-slate-200">{{ $course->code }}</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5">Cập nhật: {{ $course->updated_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-6">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-bold border-2 border-white shadow-sm">
                                    {{ substr($course->user->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-slate-600">{{ $course->user->name ?? 'Chưa phân công' }}</span>
                                <span class="text-sm font-bold text-slate-600">({{ $course->user->email ?? '' }})</span>
                            </div>
                        </td>

                        <td class="p-6">
                            @if($course->classroom)
                                <div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-600 border border-teal-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                                        {{ $course->classroom->name }}
                                    </span>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200">
                                    Môn chung
                                </span>
                            @endif
                        </td>

                        <td class="p-6">
                            @if($course->status === 'open')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Đang mở lớp
                                </span>
                            @elseif($course->status === 'closed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600 border border-red-200">
                                    Đã đóng
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    Bản nháp
                                </span>
                            @endif
                        </td>

                        <td class="p-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="group/btn flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition-colors hover:bg-blue-600 hover:text-white" title="Chỉnh sửa">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa môn học này không?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="group/btn flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors hover:bg-red-500 hover:text-white" title="Xóa">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <h3 class="font-bold text-lg text-slate-600">Chưa có dữ liệu</h3>
                                <p class="text-sm">Hãy bấm nút "Thêm mới" để tạo học phần đầu tiên.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($courses instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
@endsection