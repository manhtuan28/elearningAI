<title>Biên soạn: {{ $course->title }}</title>
@extends('layouts.admin')

<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    .ck-editor__editable_inline {
        min-height: 450px !important;
        padding: 2rem !important;
    }

    .ck-content {
        font-family: 'Inter', sans-serif;
        line-height: 1.8;
        color: #334155;
    }

    .ck-content h1,
    .ck-content h2,
    .ck-content h3,
    .ck-content h4 {
        color: #1e293b !important;
        font-weight: 800 !important;
        margin-top: 2rem !important;
        margin-bottom: 1rem !important;
        display: block !important;
    }

    .ck-content h1 {
        font-size: 2.25rem !important;
        border-bottom: 3px solid #f1f5f9;
        padding-bottom: 0.5rem;
    }

    .ck-content h2 {
        font-size: 1.875rem !important;
        border-left: 4px solid #3b82f6;
        padding-left: 1rem;
    }

    .ck-content h3 {
        font-size: 1.5rem !important;
    }

    .ck-content h4 {
        font-size: 1.25rem !important;
    }

    .ck-content ul,
    .ck-content ol {
        margin-bottom: 1.5rem !important;
        padding-left: 2.5rem !important;
    }

    .ck-content ul {
        list-style-type: disc !important;
    }

    .ck-content ol {
        list-style-type: decimal !important;
    }

    .ck-content li {
        margin-bottom: 0.5rem !important;
        display: list-item !important;
    }

    .ck-content table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 1.5rem 0 !important;
        border: 1px solid #e2e8f0 !important;
    }

    .ck-content table th,
    .ck-content table td {
        border: 1px solid #e2e8f0 !important;
        padding: 0.75rem !important;
        text-align: left !important;
    }

    .ck-content table th {
        background-color: #f8fafc !important;
        font-weight: 700 !important;
        color: #475569 !important;
    }

    .ck-content blockquote {
        border-left: 4px solid #3b82f6 !important;
        background-color: #eff6ff !important;
        padding: 1.5rem !important;
        margin: 1.5rem 0 !important;
        font-style: italic !important;
        color: #1e40af !important;
        border-radius: 0 1rem 1rem 0 !important;
    }

    .ck-content hr {
        border: none !important;
        border-top: 2px solid #e2e8f0 !important;
        margin: 2.5rem 0 !important;
    }

    .ck-content a {
        color: #2563eb !important;
        text-decoration: underline !important;
        font-weight: 600 !important;
    }
</style>

@section('content')
<div x-data="manageApp()" x-init="initEditors()">

    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">
                <a href="{{ route('instructor.courses.index') }}" class="hover:text-purple-600 transition">Khóa học</a>
                <span>/</span>
                <span>Quản lý nội dung</span>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-tight uppercase">{{ $course->title }}</h1>
        </div>

        <div class="flex items-center gap-3">
            <button @click="importModalOpen = true" class="px-5 py-3 bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Nhập Excel
            </button>

            <a href="{{ route('instructor.courses.index') }}" class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-500 hover:text-purple-600 hover:border-purple-200 transition shadow-sm flex items-center gap-2">
                &larr; Quay lại
            </a>
        </div>
    </div>

    <div x-show="importModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-[2.5rem] w-full max-w-xl shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight">Nhập nội dung từ Excel</h3>
                <button @click="importModalOpen = false" class="text-slate-400 hover:text-red-500 transition">✕</button>
            </div>
            <div class="p-8">
                <form action="{{ route('instructor.courses.import', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6 p-5 bg-blue-50 rounded-2xl border border-blue-100 text-blue-800 text-xs font-medium leading-loose">
                        <p class="font-bold text-sm mb-2">📋 Hướng dẫn nhập liệu:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            <li><strong>Chương:</strong> Chỉ điền cột 'ten_chuong' (các cột khác để trống).</li>
                            <li><strong>Bài học:</strong> Điền 'ten_bai_hoc' (để trống 'ten_chuong'). Hệ thống sẽ tự gán vào chương gần nhất bên trên.</li>
                            <li><strong>Quiz:</strong> Nhập câu hỏi vào cột 'noi_dung' theo cú pháp:<br>
                                <code class="bg-white px-1 py-0.5 rounded border border-blue-200">Câu hỏi? | Đáp án 1 | *Đáp án đúng | Đáp án 3</code>
                            </li>
                            <li><a href="{{ route('instructor.courses.import_template') }}" class="underline font-black text-blue-600 hover:text-blue-800">👉 Tải file mẫu tại đây</a></li>
                        </ul>
                    </div>

                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest">Chọn file Excel (.xlsx, .csv)</label>
                    <input type="file" name="excel_file" required accept=".xlsx, .xls, .csv" class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-slate-800 file:text-white hover:file:bg-black transition cursor-pointer bg-slate-50 rounded-xl border border-slate-200">

                    <button type="submit" class="mt-8 w-full bg-emerald-600 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em] hover:bg-emerald-700 transition shadow-xl shadow-emerald-200">
                        Xác nhận nhập
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="mb-8 p-4 bg-red-50 text-red-700 rounded-2xl font-bold border border-red-100 flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="mb-8 p-4 bg-emerald-50 text-emerald-700 rounded-2xl font-bold border border-emerald-100 flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <div class="lg:col-span-8 space-y-8 relative timeline-line">
            @foreach($course->chapters as $chapter)
            <div class="relative z-10" x-data="{ expanded: true }">
                <div class="absolute left-0 w-12 h-12 rounded-2xl bg-white border-4 border-slate-100 text-slate-700 shadow-sm flex items-center justify-center font-black text-lg z-20">
                    {{ $loop->iteration }}
                </div>

                <div class="ml-20 bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="p-5 flex items-center justify-between bg-slate-50/80 border-b border-slate-100 cursor-pointer" @click="expanded = !expanded">
                        <div class="flex-1 pr-4">
                            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ $chapter->title }}</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $chapter->lessons->count() }} Bài học</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click.stop="openAddLesson({{ $chapter->id }})" class="p-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition shadow-lg shadow-purple-200 group" title="Thêm bài học">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>

                            <button @click.stop="editChapterPrompt({{ $chapter->id }}, '{{ addslashes($chapter->title) }}')" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Sửa tên chương">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>

                            <form action="{{ route('instructor.chapters.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Xóa chương này và toàn bộ bài học bên trong?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Xóa chương">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div x-show="expanded" x-collapse class="divide-y divide-slate-50">
                        @forelse($chapter->lessons as $lesson)
                        <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shadow-sm border border-slate-100
                                    {{ $lesson->type == 'video' ? 'bg-blue-50 text-blue-500' : 
                                      ($lesson->type == 'quiz' ? 'bg-purple-50 text-purple-500' : 
                                      ($lesson->type == 'homework' ? 'bg-orange-50 text-orange-500' : 'bg-slate-100 text-slate-500')) }}">
                                    @if($lesson->type == 'video') 🎥
                                    @elseif($lesson->type == 'quiz') ❓
                                    @elseif($lesson->type == 'homework') 📝
                                    @else 📄 @endif
                                </div>

                                <div>
                                    <h4 class="font-bold text-slate-700 text-sm group-hover:text-purple-700 transition">{{ $lesson->title }}</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 bg-white border border-slate-200 px-1.5 py-0.5 rounded">{{ $lesson->type }}</span>
                                        @if($lesson->duration)
                                        <span class="text-[10px] font-medium text-slate-400">{{ $lesson->duration }} phút</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                                @if(in_array($lesson->type, ['quiz', 'homework']))
                                <a href="{{ route('instructor.lessons.submissions', $lesson->id) }}"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg border border-emerald-100 hover:bg-emerald-100 hover:border-emerald-200 transition"
                                    title="Xem bài làm của sinh viên">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    @if($lesson->submissions_count > 0)
                                    <span class="text-[10px] font-black">{{ $lesson->submissions_count }}</span>
                                    @endif
                                </a>
                                @endif

                                <button @click="openEditModal({{ json_encode($lesson) }}, '{{ route('instructor.lessons.update', $lesson->id) }}')"
                                    class="p-2 bg-white text-blue-500 rounded-lg border border-slate-200 hover:border-blue-200 hover:bg-blue-50 transition shadow-sm" title="Chỉnh sửa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>

                                <form action="{{ route('instructor.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Xóa bài học này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-white text-slate-400 rounded-lg border border-slate-200 hover:border-red-200 hover:bg-red-50 hover:text-red-500 transition shadow-sm" title="Xóa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="py-8 text-center flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-10 h-10 opacity-20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <span class="text-xs font-bold uppercase tracking-wide opacity-50">Chưa có nội dung</span>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach

            <div x-data="{ open: false }" class="ml-20">
                <button @click="open = !open" x-show="!open" class="w-full py-5 border-2 border-dashed border-slate-300 rounded-[2rem] text-slate-400 font-black uppercase text-[10px] tracking-widest hover:border-purple-500 hover:text-purple-600 hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
                    <span class="w-6 h-6 rounded-full bg-slate-200 text-white flex items-center justify-center group-hover:bg-purple-500 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg></span>
                    Thêm chương mới
                </button>
                <div x-show="open" class="bg-white p-6 rounded-[2rem] shadow-xl border border-purple-100" x-transition>
                    <form action="{{ route('instructor.chapters.store', $course->id) }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="text" name="title" required placeholder="Nhập tên chương..." class="flex-1 rounded-xl border-slate-200 text-sm font-bold focus:ring-purple-500 focus:border-purple-500 bg-slate-50">
                        <button type="submit" class="bg-purple-600 text-white px-6 rounded-xl font-bold text-xs uppercase hover:bg-purple-700 transition">Lưu</button>
                        <button type="button" @click="open = false" class="px-4 text-slate-400 font-bold text-xs hover:text-slate-600">Hủy</button>
                    </form>
                </div>

                <div x-show="importModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
                    <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight">Nhập nội dung từ Excel</h3>
                            <button @click="importModalOpen = false" class="text-slate-400 hover:text-red-500 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-8">
                            <form action="{{ route('instructor.courses.import', $course->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-6 p-4 bg-blue-50 rounded-2xl border border-blue-100 text-blue-800 text-xs font-medium leading-relaxed">
                                    <p class="font-bold mb-1">💡 Lưu ý quan trọng:</p>
                                    <ul class="list-disc pl-4 space-y-1">
                                        <li>File Excel cần có các cột: <strong>ten_chuong, ten_bai_hoc, loai</strong>.</li>
                                        <li>Cột "loai" điền: video, tai lieu, bai tap, hoac quiz.</li>
                                        <li><a href="{{ route('instructor.courses.import_template') }}" class="underline font-black hover:text-blue-600">Tải file mẫu chuẩn tại đây</a> để tránh lỗi.</li>
                                    </ul>
                                </div>

                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest">Chọn file Excel (.xlsx, .csv)</label>
                                <input type="file" name="excel_file" required accept=".xlsx, .xls, .csv" class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition cursor-pointer bg-slate-50 rounded-xl border border-slate-200">

                                <button type="submit" class="mt-8 w-full bg-slate-900 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em] hover:bg-black transition shadow-xl shadow-slate-200">
                                    Tiến hành nhập
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 lg:sticky lg:top-24 h-fit">
            <div class="bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl text-white overflow-hidden relative">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-purple-500 rounded-full blur-3xl opacity-20"></div>
                <h3 class="font-black uppercase tracking-[0.2em] text-[10px] mb-8 text-purple-400 border-b border-white/10 pb-4">Tổng quan</h3>
                <div class="space-y-5 text-sm font-medium">
                    <div class="flex justify-between items-center"><span class="text-slate-400">📝 Bài tập & Tài liệu</span><span class="font-bold">{{ $course->chapters->sum(fn($c)=>$c->lessons->whereIn('type',['document','homework'])->count()) }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-slate-400">🎥 Video bài giảng</span><span class="font-bold">{{ $course->chapters->sum(fn($c)=>$c->lessons->where('type','video')->count()) }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-slate-400">❓ Câu hỏi Quiz</span><span class="font-bold">{{ $course->chapters->sum(fn($c)=>$c->lessons->where('type','quiz')->count()) }}</span></div>
                    <div class="pt-6 mt-6 border-t border-white/10">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Tổng bài nộp</span>
                            <span class="text-xl font-black text-white">{{ \App\Models\LessonSubmission::whereIn('lesson_id', $course->chapters->flatMap->lessons->pluck('id'))->count() }}</span>
                        </div>
                        <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full w-full opacity-50"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-[2.5rem] w-full max-w-4xl shadow-2xl max-h-[95vh] overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="font-black text-2xl text-slate-800 uppercase tracking-tight" x-text="isEdit ? 'Chỉnh sửa bài học' : 'Thêm bài học mới'"></h3>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">Nhập thông tin chi tiết bên dưới</p>
                </div>
                <button @click="modalOpen = false" class="w-10 h-10 rounded-full bg-white text-slate-400 hover:text-red-500 hover:bg-red-50 border border-slate-200 flex items-center justify-center transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <form @submit.prevent="submitLesson">
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-8">
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Tiêu đề bài học</label>
                                <input type="text" x-model="lesson.title" required class="w-full rounded-2xl border-slate-200 font-bold px-5 py-4 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition shadow-sm text-slate-800" placeholder="Ví dụ: Giới thiệu tổng quan...">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Loại nội dung</label>
                                <select x-model="lesson.type" class="w-full rounded-2xl border-slate-200 font-bold px-5 py-4 bg-slate-50 focus:ring-purple-500 cursor-pointer text-slate-700">
                                    <option value="document">📄 Tài liệu đọc</option>
                                    <option value="video">🎥 Video bài giảng</option>
                                    <option value="homework">📝 Bài tập tự luận</option>
                                    <option value="quiz">❓ Trắc nghiệm (Quiz)</option>
                                </select>
                            </div>
                        </div>

                        <div x-show="lesson.type === 'video'" class="p-6 bg-slate-50 rounded-[2rem] border border-slate-200 space-y-5" x-transition>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg></span>
                                <h4 class="font-bold text-slate-700 text-sm uppercase">Cấu hình Video</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Upload Video (MP4)</label>
                                    <input type="file" @change="handleFile($event)" accept="video/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer bg-white rounded-xl border border-slate-200">
                                    <div x-show="isEdit && lesson.file_path && !file" class="mt-2 flex items-center gap-2 text-xs text-emerald-600 font-bold bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Đang dùng: <span x-text="lesson.file_path.split('/').pop()"></span></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Hoặc Link Youtube</label>
                                    <input type="text" x-model="lesson.video_url" placeholder="https://youtube.com/..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-3 font-medium">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Thời lượng (Phút)</label>
                                <input type="number" x-model="lesson.duration" class="w-32 rounded-xl border-slate-200 text-sm font-bold px-4 py-3">
                            </div>
                        </div>

                        <div x-show="['document', 'homework'].includes(lesson.type)" class="space-y-3" x-transition>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nội dung chi tiết</label>

                            <div x-show="lesson.type === 'document'" class="mb-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">File đính kèm (PDF/Word)</label>
                                <input type="file" @change="handleFile($event)" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white cursor-pointer">
                                <div x-show="isEdit && lesson.file_path && !file" class="mt-2 text-xs text-emerald-600 font-bold italic">
                                    ✓ Đã có file: <span x-text="lesson.file_path.split('/').pop()"></span>
                                </div>
                            </div>

                            <div class="rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <textarea id="editor"></textarea>
                            </div>
                        </div>

                        <div x-show="lesson.type === 'quiz'" class="p-6 bg-purple-50 rounded-[2rem] border border-purple-100 space-y-6" x-transition>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-purple-200 text-purple-700 flex items-center justify-center font-bold">?</span>
                                    <h4 class="font-bold text-purple-900 text-sm uppercase">Bộ câu hỏi</h4>
                                </div>
                                <button type="button" @click="addQuestion()" class="text-[10px] bg-white text-purple-600 px-4 py-2 rounded-xl font-black uppercase shadow-sm border border-purple-200 hover:bg-purple-600 hover:text-white transition">
                                    + Thêm câu hỏi
                                </button>
                            </div>

                            <template x-for="(q, idx) in questions" :key="idx">
                                <div class="bg-white p-6 rounded-3xl border border-purple-100 shadow-sm relative group hover:border-purple-300 transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="text-xs font-black text-purple-300 uppercase">Câu hỏi <span x-text="idx + 1"></span></span>
                                        <button type="button" @click="removeQuestion(idx)" class="text-slate-300 hover:text-red-500 transition" x-show="questions.length > 1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <input type="text" x-model="q.question" placeholder="Nhập nội dung câu hỏi..." class="w-full border-none border-b-2 border-slate-100 p-0 font-bold text-slate-800 focus:ring-0 focus:border-purple-500 bg-transparent text-sm mb-4 placeholder-slate-300">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 focus-within:border-purple-400 focus-within:bg-white transition">
                                                <input type="radio" :name="'correct_' + idx" :checked="q.correct == oIdx" @change="q.correct = oIdx" class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-slate-300 cursor-pointer">
                                                <input type="text" x-model="q.options[oIdx]" class="flex-1 bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0 p-0" :placeholder="'Đáp án ' + (oIdx + 1)">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="uploading" class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="flex justify-between mb-1"><span class="text-[10px] font-black text-blue-600 uppercase">Đang xử lý...</span><span class="text-xs font-bold text-blue-600" x-text="progress + '%'"></span></div>
                            <div class="w-full bg-white h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>

                        <button type="submit" :disabled="uploading" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black uppercase text-xs tracking-[0.2em] hover:bg-black transition shadow-xl shadow-slate-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="uploading ? 'VUI LÒNG ĐỢI...' : (isEdit ? 'CẬP NHẬT BÀI HỌC' : 'TẠO BÀI HỌC MỚI')"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function manageApp() {
        return {
            importModalOpen: false,
            modalOpen: false,
            isEdit: false,
            uploading: false,
            progress: 0,
            activeChapterId: null,
            actionUrl: '',
            lesson: {
                id: null,
                title: '',
                type: 'document',
                duration: 0,
                video_url: '',
                content: '',
                file_path: ''
            },
            file: null,
            questions: [],

            initEditors() {
                ClassicEditor.create(document.querySelector('#editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
                }).then(ed => {
                    window.lessonEditor = ed;
                    ed.editing.view.change(writer => {
                        writer.addClass('ck-content', ed.editing.view.document.getRoot());
                    });
                });
            },

            editChapterPrompt(id, oldTitle) {
                let newTitle = prompt("Nhập tên chương mới:", oldTitle);
                if (newTitle && newTitle !== oldTitle) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/instructor/chapters/${id}`;

                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';

                    let csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    let titleInput = document.createElement('input');
                    titleInput.type = 'hidden';
                    titleInput.name = 'title';
                    titleInput.value = newTitle;

                    form.appendChild(methodInput);
                    form.appendChild(csrfInput);
                    form.appendChild(titleInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            },

            openAddLesson(chapterId) {
                this.isEdit = false;
                this.activeChapterId = chapterId;
                this.lesson = {
                    id: null,
                    title: '',
                    type: 'document',
                    duration: 0,
                    video_url: '',
                    content: '',
                    file_path: ''
                };
                this.questions = [{
                    question: '',
                    options: ['', '', '', ''],
                    correct: 0
                }];
                this.file = null;
                if (window.lessonEditor) window.lessonEditor.setData('');
                this.actionUrl = `/instructor/chapters/${chapterId}/lessons`;
                this.modalOpen = true;
                this.progress = 0;
            },

            openEditModal(data, url) {
                this.isEdit = true;
                this.actionUrl = url;
                const cleanData = JSON.parse(JSON.stringify(data));
                this.lesson = {
                    id: cleanData.id,
                    title: cleanData.title,
                    type: cleanData.type,
                    duration: cleanData.duration || 0,
                    video_url: cleanData.video_url || '',
                    content: cleanData.content || '',
                    file_path: cleanData.file_path || ''
                };
                this.file = null;

                if (window.lessonEditor) window.lessonEditor.setData(this.lesson.content || '');

                if (cleanData.type === 'quiz' && cleanData.content) {
                    try {
                        this.questions = JSON.parse(cleanData.content);
                    } catch (e) {
                        this.questions = [{
                            question: '',
                            options: ['', '', '', ''],
                            correct: 0
                        }];
                    }
                } else {
                    this.questions = [{
                        question: '',
                        options: ['', '', '', ''],
                        correct: 0
                    }];
                }

                this.modalOpen = true;
                this.progress = 0;
            },

            addQuestion() {
                this.questions.push({
                    question: '',
                    options: ['', '', '', ''],
                    correct: 0
                });
            },
            removeQuestion(idx) {
                this.questions.splice(idx, 1);
            },
            handleFile(e) {
                const file = e.target.files[0];
                this.file = file;

                if (this.lesson.type === 'video' && file && file.type.startsWith('video/')) {
                    const videoNode = document.createElement('video');
                    videoNode.preload = 'metadata';
                    videoNode.src = URL.createObjectURL(file);

                    videoNode.onloadedmetadata = () => {
                        URL.revokeObjectURL(videoNode.src);
                        const durationSeconds = videoNode.duration;

                        this.lesson.duration = Math.round(durationSeconds / 60);

                        if (this.lesson.duration === 0) this.lesson.duration = 1;
                    }
                }
            },

            async submitLesson() {
                this.uploading = true;
                this.progress = 0;
                const fd = new FormData();
                fd.append('title', this.lesson.title);
                fd.append('type', this.lesson.type);
                fd.append('duration', this.lesson.duration);
                fd.append('video_url', this.lesson.video_url || '');

                if (this.isEdit) fd.append('_method', 'PUT');
                if (this.file) fd.append('file_upload', this.file);

                if (['document', 'homework'].includes(this.lesson.type) && window.lessonEditor) {
                    const fieldName = this.lesson.type === 'document' ? 'content_doc' : 'content_homework';
                    fd.append(fieldName, window.lessonEditor.getData());
                }

                if (this.lesson.type === 'quiz') {
                    this.questions.forEach((q, i) => {
                        fd.append(`quiz[${i}][question]`, q.question);
                        fd.append(`quiz[${i}][correct]`, q.correct);
                        q.options.forEach(opt => fd.append(`quiz[${i}][options][]`, opt));
                    });
                }

                try {
                    await axios.post(this.actionUrl, fd, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        onUploadProgress: (p) => this.progress = Math.round((p.loaded * 100) / p.total)
                    });
                    location.reload();
                } catch (err) {
                    alert('Lỗi: ' + (err.response?.data?.message || err.message));
                    this.uploading = false;
                }
            }
        }
    }
</script>
@endsection