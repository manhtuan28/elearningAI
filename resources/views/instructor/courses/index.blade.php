<title>Danh sách học phần - Giảng viên</title>
@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Học phần của tôi</h1>
            <p class="text-slate-500 font-medium">Danh sách toàn bộ các lớp tín chỉ bạn phụ trách.</p>
        </div>
        
        <form action="{{ route('instructor.courses.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên môn học..." 
                   class="pl-10 pr-4 py-2.5 rounded-full border-none bg-white text-sm font-bold text-slate-800 placeholder-slate-400 shadow-sm focus:ring-2 focus:ring-purple-500 w-64">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Thông tin Học phần</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Lớp / Sĩ số</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Tiến độ biên soạn</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                    <tr class="hover:bg-purple-50/30 transition group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-black shadow-sm group-hover:scale-110 transition">
                                    {{ substr($course->title, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm group-hover:text-purple-700 transition">{{ $course->title }}</h4>
                                    <span class="text-xs font-bold text-slate-400">{{ $course->code ?? 'Chưa có mã' }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                @if($course->classroom)
                                    <span class="inline-flex w-fit items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-teal-50 text-teal-600 border border-teal-100 uppercase">
                                        {{ $course->classroom->name }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-600">
                                        {{ $course->classroom->students_count }} sinh viên
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-slate-400 italic">Môn chung</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($course->status === 'open')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-600 border border-green-200 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Đang dạy
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 uppercase">
                                    Đóng / Nháp
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('instructor.courses.students', $course->id) }}" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition border border-transparent hover:border-teal-100" title="Danh sách sinh viên">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </a>

                                <a href="{{ route('instructor.courses.manage', $course->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-purple-100 text-purple-600 rounded-lg hover:bg-purple-600 hover:text-white hover:border-purple-600 transition shadow-sm font-bold text-xs uppercase tracking-wider">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Biên soạn
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            Chưa có học phần nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($courses->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $courses->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection