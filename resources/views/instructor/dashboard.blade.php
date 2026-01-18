<title>E-Learning AI - Instructor Dashboard</title>
@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800">Khu vực Giảng dạy</h2>
            <p class="text-slate-500 font-medium">Quản lý các học phần và lớp tín chỉ được phân công.</p>
        </div>
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
                <span class="text-sm font-bold text-slate-400">Tổng sinh viên theo học</span>
            </div>
        </div>
    </div>

    <div class="rounded-[2rem] border border-slate-100 bg-white shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="px-6 py-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-black text-xl text-slate-800">Danh sách lớp học phần</h3>
            <div class="flex gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 py-1">Học kỳ:</span>
                <span class="text-xs font-bold text-purple-600 py-1">2025-2026</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Tên môn học</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Lớp / Mã HP</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Sĩ số</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($myCourses as $course)
                    <tr class="hover:bg-purple-50/30 transition">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold shadow-sm">
                                    {{ substr($course->title, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $course->title }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Ngày tạo: {{ $course->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col items-start gap-1">
                                @if($course->classroom)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-teal-50 text-teal-600 border border-teal-100 uppercase">
                                        {{ $course->classroom->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-slate-100 text-slate-500 border border-slate-200 uppercase">
                                        Môn chung
                                    </span>
                                @endif
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $course->code ?? 'N/A' }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2 overflow-hidden">
                                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-slate-200 flex items-center justify-center text-[8px] font-bold text-slate-500">SV</div>
                                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-purple-100 flex items-center justify-center text-[8px] font-bold text-purple-500">SV</div>
                                </div>

                                @if($course->classroom)
                                    <span class="text-sm font-bold text-slate-700">
                                        {{ $course->classroom->students_count }} sinh viên
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-slate-400 italic">
                                        (Chưa chốt DS)
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($course->status === 'open')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-600 border border-green-200 uppercase tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Đang dạy
                                </span>
                            @elseif($course->status === 'closed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 border border-red-200 uppercase tracking-wide">
                                    Đã đóng
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wide">
                                    Chưa mở
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('instructor.courses.manage', $course->id) }}" class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 hover:shadow-lg hover:shadow-purple-200 transition group shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="text-xs font-bold uppercase tracking-wider">Biên soạn</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-bold">Chưa có học phần nào được phân công.</span>
                                <span class="text-xs mt-1">Vui lòng liên hệ Admin/Đào tạo.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection