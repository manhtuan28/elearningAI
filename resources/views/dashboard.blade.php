<title>E-Learning AI - Dashboard</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            {{ __('Tổng quan') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="relative overflow-hidden bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl sm:rounded-[2.5rem] p-8 lg:p-12">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-gradient-to-br from-blue-400/30 to-purple-400/30 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/50 text-[10px] font-black uppercase tracking-widest text-blue-600 mb-4 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            Hệ thống hoạt động bình thường
                        </div>
                        <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                            Xin chào, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">{{ Auth::user()->name }}</span>!
                        </h1>
                        <p class="mt-4 text-slate-600 text-lg font-medium max-w-xl">
                            Chào mừng quay trở lại. Hôm nay là <span class="text-slate-900 font-bold">{{ date('d/m/Y') }}</span>, chúc cậu một ngày làm việc hiệu quả.
                        </p>
                    </div>

                    <div class="hidden md:block relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur opacity-40 group-hover:opacity-60 transition duration-500"></div>
                        <div class="relative w-24 h-24 rounded-full bg-white p-1 shadow-2xl ring-4 ring-white/50">
                            <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-3xl font-black text-slate-700 uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->isAdmin() || auth()->user()->isInstructor())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- CARD ADMIN --}}
                @if(auth()->user()->isAdmin())
                <div class="group relative overflow-hidden rounded-[2rem] bg-slate-900 p-8 shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex items-start justify-between">
                            <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-sm">
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <span class="bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Admin</span>
                        </div>
                        <h3 class="mt-6 text-2xl font-black text-white">Quản Trị Hệ Thống</h3>
                        <p class="mt-2 text-slate-400 text-sm">Trung tâm điều khiển user, khóa học và báo cáo.</p>
                        <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-flex items-center gap-2 text-blue-400 font-bold hover:text-white transition-colors group-hover:translate-x-2 duration-300">
                            Truy cập ngay <span class="text-lg">&rarr;</span>
                        </a>
                    </div>
                </div>
                @endif

                {{-- CARD GIẢNG VIÊN --}}
                @if(auth()->user()->isInstructor())
                <div class="group relative overflow-hidden rounded-[2rem] bg-indigo-900 p-8 shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex items-start justify-between">
                            <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-sm">
                                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <span class="bg-purple-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Instructor</span>
                        </div>
                        <h3 class="mt-6 text-2xl font-black text-white">Khu Vực Giảng Dạy</h3>
                        <p class="mt-2 text-indigo-200 text-sm">Quản lý lớp học, bài giảng và học viên của bạn.</p>
                        <a href="{{ route('instructor.dashboard') }}" class="mt-6 inline-flex items-center gap-2 text-purple-300 font-bold hover:text-white transition-colors group-hover:translate-x-2 duration-300">
                            Soạn bài giảng <span class="text-lg">&rarr;</span>
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <div>
                <div class="flex items-center justify-between mb-6 px-2">
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                        <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                        Học phần dành cho lớp của bạn
                    </h3>
                    <a href="#" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition uppercase tracking-widest">Xem tất cả</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if(isset($activeCourses) && count($activeCourses) > 0)
                    @foreach($activeCourses as $course)
                    <a href="{{ route('learning.detail', $course->id) }}" class="block group">
                        <div class="bg-white/80 backdrop-blur-md rounded-[2rem] p-5 shadow-lg border border-white hover:shadow-xl hover:scale-[1.02] transition-all duration-300 flex flex-col h-full relative">

                            <div class="h-40 bg-slate-200 rounded-2xl mb-4 overflow-hidden relative shrink-0">
                                @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-500 font-black text-4xl uppercase">
                                    {{ substr($course->title, 0, 1) }}
                                </div>
                                @endif

                                <div class="absolute top-3 left-3">
                                    @if($course->classroom)
                                    <span class="bg-purple-600/90 backdrop-blur-sm text-white text-[9px] font-black px-2.5 py-1 rounded-lg uppercase shadow-lg border border-white/20">
                                        Lớp: {{ $course->classroom->name }}
                                    </span>
                                    @else
                                    <span class="bg-slate-700/80 backdrop-blur-sm text-white text-[9px] font-black px-2.5 py-1 rounded-lg uppercase shadow-lg border border-white/20">
                                        Môn chung
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col flex-1">
                                <div class="mb-3">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                                        {{ $course->code ?? 'Học phần' }}
                                    </span>
                                </div>

                                <h4 class="font-bold text-lg text-slate-800 leading-snug line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors">
                                    {{ $course->title }}
                                </h4>

                                <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-[10px] font-bold text-purple-600 shadow-inner uppercase">
                                            {{ substr($course->user->name ?? 'G', 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-500 truncate max-w-[100px]">
                                            {{ $course->user->name ?? 'Giảng viên' }}
                                        </span>
                                    </div>

                                    <span class="text-xs font-black text-blue-600 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        Vào học
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                    @else
                    <div class="col-span-full py-20 text-center bg-white/40 rounded-[2.5rem] border border-dashed border-slate-300">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-slate-500 font-bold text-lg">Hiện chưa có học phần nào dành riêng cho lớp của bạn.</p>
                            <p class="text-slate-400 text-sm">Vui lòng liên hệ Giảng viên hoặc Phòng đào tạo để được hướng dẫn.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>