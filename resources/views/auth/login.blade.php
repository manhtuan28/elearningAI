<x-guest-layout>
    <div class="flex min-h-screen">
        <a href="/" class="absolute top-6 left-6 z-50 flex items-center space-x-2 text-sm font-bold text-gray-600 hover:text-blue-600 transition-all bg-white/80 backdrop-blur px-4 py-2 rounded-full shadow-sm border border-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Quay lại trang chủ</span>
        </a>

        <div class="w-full lg:w-[40%] flex items-center justify-center p-8 lg:p-16 bg-white relative">
            <div class="w-full max-w-sm">
                <div class="mb-10 text-center lg:text-left">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-xl mb-4 shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 21a10.003 10.003 0 008.384-4.562l.054.09m-3.44 2.04l.054-.09a10.003 10.003 0 001.616-9.571m-3.44 2.04l.054-.09A10.003 10.003 0 0012 3a10.003 10.003 0 00-8.384 4.562l.054.09m3.44-2.04l.054-.09z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Hệ thống AI Login</h2>
                    <p class="text-gray-500 mt-2 font-medium italic text-sm">Cổng thông tin dành cho sinh viên nội bộ</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <x-auth-session-status class="mb-4 text-sm font-medium text-green-600" :status="session('status')" />

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Tài khoản Email</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 focus:bg-white outline-none transition-all shadow-sm font-medium"
                                placeholder="name@university.edu.vn">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-red-500" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2 ml-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Mật khẩu</label>
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:underline">Quên?</a>
                        </div>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" name="password" required
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 focus:bg-white outline-none transition-all shadow-sm font-medium"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-red-500" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center group cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <span class="ml-2 text-sm font-bold text-gray-500 group-hover:text-gray-700 transition-colors">Lưu phiên đăng nhập</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-2xl hover:bg-blue-600 transition-all duration-300 shadow-xl hover:shadow-blue-200 transform hover:-translate-y-1">
                        Đăng Nhập Ngay
                    </button>
                </form>

                <div class="mt-12 p-4 rounded-2xl bg-orange-50 border border-orange-100">
                    <p class="text-[10px] leading-relaxed text-orange-700 font-bold uppercase tracking-wider text-center">
                        Lưu ý: Bạn đang đăng nhập vào hệ thống bảo mật. <br> Vui lòng không chia sẻ tài khoản cho người khác.
                    </p>
                </div>
            </div>
        </div>

        <div class="hidden lg:block lg:w-[60%] relative bg-blue-600 overflow-hidden">
            <img class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40" 
                 src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1200&q=80" alt="Cyber Security">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-transparent to-transparent"></div>
            
            <div class="relative h-full flex flex-col justify-between p-16">
                <div class="flex items-center space-x-2 text-white">
                    <span class="font-black text-2xl tracking-tighter italic">ELEANING.AI</span>
                </div>
                
                <div class="max-w-xl text-white">
                    <span class="inline-block px-3 py-1 rounded-full bg-white/10 backdrop-blur text-[10px] font-bold tracking-widest uppercase mb-4">Phiên bản 2026</span>
                    <h3 class="text-5xl font-black leading-tight mb-6 italic">Kiến tạo tương lai cùng <br> Trợ lý học tập AI.</h3>
                    <p class="text-blue-100 text-lg font-medium opacity-80 leading-relaxed">
                        Hệ thống tự động theo dõi tiến độ, phát hiện khó khăn và đề xuất giải pháp giúp bạn đạt kết quả tốt nhất trong từng môn học.
                    </p>
                </div>

                <div class="flex items-center justify-between text-white/60 text-xs font-bold uppercase tracking-widest">
                    <span>© Copyright 2026</span>
                    <div class="flex space-x-6">
                        <a href="#" class="hover:text-white transition-colors">Điều khoản</a>
                        <a href="#" class="hover:text-white transition-colors">Hỗ trợ sinh viên</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>