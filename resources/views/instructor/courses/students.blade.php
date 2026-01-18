<title>Danh sách sinh viên: {{ $course->title }}</title>
@extends('layouts.admin')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <div class="flex items-center gap-2 text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">
            <a href="{{ route('instructor.courses.index') }}" class="hover:text-purple-600 transition">Khóa học</a>
            <span>/</span>
            <span>Sinh viên</span>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-tight uppercase">{{ $course->title }}</h1>
        <div class="mt-3 flex items-center gap-4">
            <span class="px-3 py-1 rounded-lg bg-teal-50 text-teal-700 text-[11px] font-black uppercase border border-teal-100">
                Lớp: {{ $course->classroom ? $course->classroom->name : 'Chưa gán lớp' }}
            </span>
            <span class="text-xs font-bold text-slate-500">{{ $students->count() }} Sinh viên</span>
        </div>
    </div>
    <a href="{{ route('instructor.courses.index') }}" class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-500 hover:text-purple-600 hover:border-purple-200 transition shadow-sm flex items-center gap-2 group">
        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Quay lại
    </a>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
    @if($students->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="p-6 pl-8 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Thông tin sinh viên</th>
                    <!-- <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Mã sinh viên</th> -->
                    <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Email liên hệ</th>
                    <th class="p-6 pr-8 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Trạng thái</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($students as $student)
                <tr class="group hover:bg-slate-50/50 transition-colors duration-200">
                    <td class="p-6 pl-8">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-black text-sm shadow-md shadow-indigo-200">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm group-hover:text-indigo-700 transition">{{ $student->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Sinh viên</p>
                            </div>
                        </div>
                    </td>
                    <!-- <td class="p-6">
                        <span class="font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-lg text-xs">{{ $student->code ?? 'N/A' }}</span>
                    </td> -->
                    <td class="p-6">
                        <span class="text-sm font-medium text-slate-500 italic">{{ $student->email }}</span>
                    </td>
                    <td class="p-6 pr-8 text-right">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Đang học
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="py-20 flex flex-col items-center justify-center text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <h3 class="text-lg font-black text-slate-800">Danh sách trống</h3>
        <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto">Lớp học phần này chưa có sinh viên nào được thêm vào.</p>
    </div>
    @endif
</div>
@endsection