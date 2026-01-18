<title>E-Learning AI - Nhập tài khoản hàng loạt</title>
@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Nhập User Hàng Loạt</h1>
            <p class="text-slate-500 font-medium">Thêm nhiều tài khoản cùng lúc qua file Excel.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-slate-500 font-bold hover:text-blue-600 transition">&larr; Quay lại</a>
    </div>

    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">

        <div class="mb-8 bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-bold text-blue-700 text-sm uppercase mb-1">Hướng dẫn nhập liệu</h4>
                <p class="text-xs text-blue-600 leading-relaxed mb-2">
                    1. Vui lòng sử dụng file mẫu chuẩn bên dưới.<br>
                    2. Cột <strong>Vai trò</strong> đã có sẵn danh sách chọn (Sinh viên, Giảng viên...), vui lòng không tự nhập tay sai định dạng.<br>
                    3. Nếu để trống cột <strong>Mật khẩu</strong>, hệ thống sẽ tự đặt là <code class="bg-white px-1 rounded border border-blue-200">12345678</code>.
                </p>

                <a href="{{ route('admin.users.import.template') }}" class="inline-flex items-center gap-2 mt-2 px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-md hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Tải file mẫu Excel chuẩn (.xlsx)
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border-l-4 border-green-500">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded border-l-4 border-red-500">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 text-red-600 rounded border-l-4 border-red-400">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.users.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="relative w-full h-48 bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer group mb-6">
                <input type="file" name="file" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="showFileName(this)" required>

                <div class="flex flex-col items-center group-hover:-translate-y-1 transition-transform duration-300">
                    <svg class="w-12 h-12 mb-3 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <span class="font-bold text-sm text-slate-500 group-hover:text-blue-600">Kéo thả hoặc Click để chọn file Excel</span>
                    <p id="file-name" class="mt-2 text-xs font-bold text-emerald-600 hidden"></p>
                </div>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 text-white font-black hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-1">
                TIẾN HÀNH NHẬP DỮ LIỆU
            </button>
        </form>
    </div>
</div>

<script>
    function showFileName(input) {
        if (input.files && input.files[0]) {
            document.getElementById('file-name').textContent = 'Đã chọn: ' + input.files[0].name;
            document.getElementById('file-name').classList.remove('hidden');
        }
    }
</script>
@endsection