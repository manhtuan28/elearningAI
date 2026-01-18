<title>E-Learning AI - Instructor Dashboard</title>
@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-3xl font-black text-slate-800">Khu vực Giảng dạy</h2>
        <p class="text-slate-500 font-medium">Quản lý các học phần được phân công.</p>
    </div>

    <a href="#" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-purple-600 px-6 py-3 text-center font-bold text-white hover:bg-purple-700 lg:px-6 shadow-lg shadow-purple-200 transition-all hover:-translate-y-1">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tạo học phần mới
    </a>
</div>

<div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-10">
    <div class="rounded-[1.5rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 translate-y-[-50%] rounded-full bg-purple-100/50 group-hover:scale-110 transition-transform"></div>

        <div class="relative z-10">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 text-purple-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h4 class="text-3xl font-black text-slate-800">{{ $totalCourses }}</h4>
            <span class="text-sm font-bold text-slate-400">Lớp / Học phần phụ trách</span>
        </div>
    </div>

    <div class="rounded-[1.5rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 translate-y-[-50%] rounded-full bg-blue-100/50 group-hover:scale-110 transition-transform"></div>

        <div class="relative z-10">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h4 class="text-3xl font-black text-slate-800">{{ $totalStudents }}</h4>
            <span class="text-sm font-bold text-slate-400">Tổng sinh viên</span>
        </div>
    </div>
</div>

<div class="rounded-[2rem] border border-slate-100 bg-white shadow-xl shadow-slate-200/50 overflow-hidden">
    <div class="px-6 py-6 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-black text-xl text-slate-800">Danh sách lớp học phần</h3>
        <div class="flex gap-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 py-1">Học kỳ:</span>
            <button class="text-xs font-bold text-purple-600">2025-2026</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Tên môn học</th>
                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Mã học phần</th>
                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Sĩ số</th>
                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($myCourses as $course)
                <tr class="hover:bg-purple-50/30 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                                {{ substr($course->title, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $course->title }}</p>
                                <p class="text-xs text-slate-400">Ngày tạo: {{ $course->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-600">
                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold">
                            {{ $course->code ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex -space-x-2 overflow-hidden items-center">
                            <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-slate-200"></div>
                            <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-slate-300"></div>
                            <span class="text-xs font-bold text-slate-500 ml-2">0 SV</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button class="p-2 text-slate-400 hover:text-purple-600 transition" title="Chỉnh sửa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                            <button class="p-2 text-slate-400 hover:text-red-500 transition" title="Xóa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                        <span class="font-bold block mb-1">Chưa có học phần nào được phân công.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection