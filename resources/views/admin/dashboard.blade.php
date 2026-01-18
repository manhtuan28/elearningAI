<title>E-Learning AI - Admin Dashboard</title>
@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-3xl font-black text-slate-800">
            Tổng quan hệ thống
        </h2>
        
        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-blue-600 px-6 py-3 text-center font-bold text-white hover:bg-blue-700 lg:px-6 shadow-lg shadow-blue-200 transition-all hover:-translate-y-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Thêm học phần mới
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-10">
        
        <div class="rounded-[1.5rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800">{{ $totalStudents }}</h4>
                <span class="text-sm font-bold text-slate-400">Học viên hệ thống</span>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-50 text-purple-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800">{{ $totalInstructors }}</h4>
                <span class="text-sm font-bold text-slate-400">Giảng viên & Admin</span>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-50 text-orange-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800">{{ $totalCourses }}</h4>
                <span class="text-sm font-bold text-slate-400">Học phần hệ thống</span>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-pink-50 text-pink-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800">{{ $totalMajors }}</h4>
                <span class="text-sm font-bold text-slate-400">Chuyên ngành đào tạo</span>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-teal-50 text-teal-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800">{{ $totalClassrooms }}</h4>
                <span class="text-sm font-bold text-slate-400">Lớp hành chính</span>
            </div>
        </div>

    </div>

    <div class="rounded-[2rem] border border-slate-100 bg-white shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="px-6 py-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-black text-xl text-slate-800">Học phần mới nhất</h3>
            <a href="{{ route('admin.courses.index') }}" class="text-sm font-bold text-blue-600 hover:underline">Xem tất cả</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Tên học phần</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentCourses as $course)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                    {{ substr($course->title, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 line-clamp-1 max-w-[200px]">{{ $course->title }}</p>
                                    <p class="text-xs text-slate-400">Mã: {{ $course->code ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            @if($course->status === 'open')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Mở lớp
                                </span>
                            @elseif($course->status === 'closed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">
                                    Đã đóng
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">
                                    Nháp
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-xs font-bold text-slate-500">
                            {{ $course->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="text-sm font-bold text-blue-600 hover:text-blue-800">Chi tiết</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <span class="font-bold">Chưa có dữ liệu nào!</span>
                                <span class="text-xs mt-1">Hãy bấm nút "Thêm mới" ở trên.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection