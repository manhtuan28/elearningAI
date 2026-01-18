<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Admin Portal') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 antialiased selection:bg-blue-500 selection:text-white">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <aside class="absolute left-0 top-0 z-50 flex h-screen w-72 flex-col overflow-y-hidden bg-slate-900 duration-300 ease-linear lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <div class="flex items-center justify-center gap-2 px-6 py-8">
                <a href="{{ route('dashboard') }}" class="text-2xl font-black italic tracking-tighter text-white uppercase">
                    Admin<span class="text-blue-500">Panel</span>
                </a>
            </div>

            <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6 space-y-2">

                {{-- 1. KHU VỰC DÀNH CHO ADMIN --}}
                @if(auth()->user()->isAdmin())
                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2 mt-4">Quản trị hệ thống</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="group relative flex items-center gap-2.5 rounded-xl px-4 py-3 font-bold duration-300 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Tổng quan Admin
                </a>

                <a href="{{ route('admin.courses.index') }}"
                    class="group relative flex items-center gap-2.5 rounded-xl px-4 py-3 font-bold text-slate-400 duration-300 ease-in-out {{ request()->routeIs('admin.courses.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Quản lý Học Phần
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="group relative flex items-center gap-2.5 rounded-xl px-4 py-3 font-bold text-slate-400 duration-300 ease-in-out {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Quản lý Tài khoản
                </a>

                <div class="space-y-1">
                    <p class="px-4 text-xs font-black uppercase text-slate-400 mt-6 mb-2">Đào tạo</p>

                    <a href="{{ route('admin.majors.index') }}"
                        class="group relative flex items-center gap-2.5 rounded-xl px-4 py-3 font-bold text-slate-400 duration-300 ease-in-out {{ request()->routeIs('admin.majors.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Quản lý Chuyên ngành
                    </a>

                    <a href="{{ route('admin.classrooms.index') }}"
                        class="group relative flex items-center gap-2.5 rounded-xl px-4 py-3 font-bold text-slate-400 duration-300 ease-in-out {{ request()->routeIs('admin.classrooms.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Quản lý Lớp học
                    </a>
                </div>

                @endif

                {{-- 2. KHU VỰC DÀNH CHO GIẢNG VIÊN --}}
                @if(auth()->user()->isInstructor())
                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2 mt-4">Khu vực Giảng viên</p>

                <a href="{{ route('instructor.dashboard') }}"
                    class="group relative flex items-center gap-2.5 rounded-xl px-4 py-3 font-bold duration-300 ease-in-out {{ request()->routeIs('instructor.dashboard') ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Bảng điều khiển
                </a>

                <a href="{{ route('instructor.courses.index') }}"
                    class="group relative flex items-center gap-2.5 rounded-xl px-4 py-3 font-bold duration-300 ease-in-out {{ request()->routeIs('instructor.courses.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Học phần của tôi
                </a>
                @endif

                {{-- 3. LINK CHUNG (Thoát về trang sinh viên) --}}
                <div class="pt-4 pb-2">
                    <div class="h-px bg-slate-800 mx-4"></div>
                </div>

                <a href="{{ route('dashboard') }}"
                    class="group relative flex items-center gap-2.5 rounded-xl px-4 py-3 font-bold text-slate-400 duration-300 ease-in-out hover:bg-white/5 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Về trang Học viên
                </a>
            </nav>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">

            <header class="sticky top-0 z-40 flex w-full bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-100">
                <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-2 md:px-6 2xl:px-11">

                    <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
                        <button @click="sidebarOpen = !sidebarOpen" class="block rounded-sm border border-slate-200 bg-white p-1.5 shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="hidden sm:block">
                        <form action="#" method="POST">
                            <div class="relative group">
                                <button class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>

                                <input type="text"
                                    placeholder="Tìm kiếm nhanh..."
                                    class="w-full xl:w-96 bg-slate-100 text-slate-800 text-sm font-bold placeholder-slate-400 border-none rounded-full py-3 pl-12 pr-14 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:shadow-lg transition-all duration-300 ease-in-out" />

                                <div class="absolute right-3 top-1/2 -translate-y-1/2 hidden xl:flex items-center gap-1 pointer-events-none">
                                    <span class="bg-white text-slate-400 text-[10px] font-bold px-2 py-1 rounded-md border border-slate-200 shadow-sm">⌘ K</span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="flex items-center gap-3 2xsm:gap-7">
                        <div class="text-right hidden md:block">
                            <span class="text-sm font-bold text-slate-800 block">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-slate-500 font-bold uppercase">
                                @if(Auth::user()->isAdmin()) Administrator
                                @elseif(Auth::user()->isInstructor()) Instructor
                                @endif
                            </span>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-black shadow-lg shadow-blue-200">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>