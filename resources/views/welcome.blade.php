<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learning AI - Hệ Thống Học Tập Thông Minh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/js/tailwindcss.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="antialiased bg-white text-gray-900">

    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center space-x-2">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="text-2xl font-black text-gray-900 tracking-tight">E-Learning <span class="text-blue-600">AI</span></span>
                </div>

                <div class="flex items-center space-x-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-700 hover:text-blue-600 transition">Vào Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-semibold transition">Đăng nhập</a>
                            <button class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform hover:-translate-y-0.5">Liên hệ nhà trường</button>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-16 pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2 space-y-8 z-10">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 border border-blue-100">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2 animate-pulse"></span>
                    <span class="text-blue-700 text-sm font-bold uppercase tracking-wider">Hệ thống cấp tài khoản nội bộ</span>
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-gray-900 leading-tight">
                    Cánh cửa đến với <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 italic">Tri thức AI</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-xl leading-relaxed">
                    Hệ thống học tập dành riêng cho sinh viên với công nghệ AI tiên tiến, giúp bạn vượt qua mọi kỳ thi một cách tự tin nhất.
                </p>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-lg hover:bg-blue-700 shadow-xl shadow-blue-200 transition">VÀO HỌC NGAY</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-lg hover:bg-blue-700 shadow-xl shadow-blue-200 transition">ĐĂNG NHẬP</a>
                        <p class="text-sm text-gray-400 font-medium max-w-[150px]">Vui lòng dùng tài khoản nhà trường cấp</p>
                    @endauth
                </div>
            </div>
            <div class="lg:w-1/2 mt-20 lg:mt-0 relative">
                <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                <img class="relative rounded-3xl shadow-2xl transform hover:scale-[1.02] transition duration-500" src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80" alt="Student learning">
            </div>
        </div>
    </div>

    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center text-white">
            <div>
                <p class="text-4xl font-black text-blue-500">1,200+</p>
                <p class="text-gray-400 mt-2 font-medium">Sinh viên</p>
            </div>
            <div>
                <p class="text-4xl font-black text-blue-500">50+</p>
                <p class="text-gray-400 mt-2 font-medium">Khóa học</p>
            </div>
            <div>
                <p class="text-4xl font-black text-blue-500">95%</p>
                <p class="text-gray-400 mt-2 font-medium">Tỷ lệ qua môn</p>
            </div>
            <div>
                <p class="text-4xl font-black text-blue-500">24/7</p>
                <p class="text-gray-400 mt-2 font-medium">AI Hỗ trợ</p>
            </div>
        </div>
    </div>

    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16">
            <h2 class="text-blue-600 font-black tracking-widest uppercase text-sm mb-4 italic">Công nghệ dẫn đầu</h2>
            <p class="text-4xl font-black text-gray-900">Tính năng thông minh từ AI</p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group p-8 bg-white rounded-3xl border border-gray-100 hover:border-blue-500 transition duration-300 shadow-sm hover:shadow-xl">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-blue-600 group-hover:scale-110 transition duration-300">📊</div>
                <h3 class="text-xl font-black text-gray-900 mb-4">Dự báo nguy cơ</h3>
                <p class="text-gray-600 leading-relaxed font-medium">Hệ thống phân tích lịch sử học tập để cảnh báo sớm nếu bạn có nguy cơ không đạt môn học.</p>
            </div>
            <div class="group p-8 bg-white rounded-3xl border border-gray-100 hover:border-blue-500 transition duration-300 shadow-sm hover:shadow-xl">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-blue-600 group-hover:scale-110 transition duration-300">🎯</div>
                <h3 class="text-xl font-black text-gray-900 mb-4">Lộ trình cá nhân</h3>
                <p class="text-gray-600 leading-relaxed font-medium">Tự động gợi ý các bài giảng bổ trợ dựa trên những phần kiến thức bạn còn yếu.</p>
            </div>
            <div class="group p-8 bg-white rounded-3xl border border-gray-100 hover:border-blue-500 transition duration-300 shadow-sm hover:shadow-xl">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-blue-600 group-hover:scale-110 transition duration-300">📱</div>
                <h3 class="text-xl font-black text-gray-900 mb-4">Học tập đa nền tảng</h3>
                <p class="text-gray-600 leading-relaxed font-medium">Truy cập và học tập mọi lúc mọi nơi trên mọi thiết bị: Laptop, Tablet, Smartphone.</p>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400 font-medium">© 2026 E-Learning AI System. Được thiết kế cho tương lai giáo dục.</p>
        </div>
    </footer>

</body>
</html>