<!DOCTYPE html>
<html>
<head>
    <title>E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg p-4">
        <h1 class="text-2xl font-bold text-blue-600">Hệ thống Học trực tuyến</h1>
    </nav>

    <div class="container mx-auto mt-10">
        <h2 class="text-xl font-semibold mb-6">Khóa học dành cho bạn</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-blue-500 font-semibold">Giảng viên: {{ $course->instructor->name }}</span>
                        <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Xem khóa học</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>