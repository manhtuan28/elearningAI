<nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <x-application-logo class="block h-9 w-auto fill-current text-blue-600 group-hover:scale-110 transition-transform duration-300" />
                        <span class="font-black text-xl tracking-tighter italic text-slate-800 group-hover:text-blue-600 transition-colors uppercase">E-Learning<span class="text-blue-500">AI</span></span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- 1. Link chung cho Sinh viên (Mọi người đều thấy) --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold text-slate-600 hover:text-blue-600">
                        {{ __('Tổng quan') }}
                    </x-nav-link>

                    {{-- 2. Link dành riêng cho ADMIN --}}
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" 
                            class="text-blue-600 font-black border-blue-500 hover:text-blue-800 hover:border-blue-700 transition">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Quản trị viên
                            </span>
                        </x-nav-link>
                    @endif

                    {{-- 3. Link dành riêng cho GIẢNG VIÊN (Đã sửa lại Route chuẩn) --}}
                    @if(Auth::user()->isInstructor())
                        <x-nav-link :href="route('instructor.dashboard')" :active="request()->routeIs('instructor.*')" 
                            class="text-purple-600 font-black border-purple-500 hover:text-purple-800 hover:border-purple-700 transition">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                Giảng dạy
                            </span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-full text-slate-600 bg-slate-100 hover:text-slate-800 hover:bg-white hover:shadow-md focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-gray-400 border-b border-gray-100 uppercase font-black tracking-widest">
                            @if(Auth::user()->isAdmin()) <span class="text-blue-500">Administrator</span>
                            @elseif(Auth::user()->isInstructor()) <span class="text-purple-500">Instructor</span>
                            @else Student @endif
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="font-medium">
                            {{ __('Hồ sơ cá nhân') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    class="text-red-600 font-bold hover:bg-red-50"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Đăng xuất') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100 shadow-xl">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Tổng quan') }}
            </x-responsive-nav-link>
            
            {{-- Mobile Link cho Admin --}}
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-blue-600 font-bold bg-blue-50">
                    {{ __('Khu vực Quản trị') }}
                </x-responsive-nav-link>
            @endif

            {{-- Mobile Link cho Giảng viên (Đã sửa Route) --}}
            @if(Auth::user()->isInstructor())
                <x-responsive-nav-link :href="route('instructor.dashboard')" :active="request()->routeIs('instructor.*')" class="text-purple-600 font-bold bg-purple-50">
                    {{ __('Khu vực Giảng dạy') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Hồ sơ') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            class="text-red-600"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Đăng xuất') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>