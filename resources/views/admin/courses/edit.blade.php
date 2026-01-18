<title>E-Learning AI - Chỉnh sửa học phần</title>
@extends('layouts.admin')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<style>
    .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 10px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-weight: 700;
        color: #334155;
        padding-left: 1rem;
    }

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
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Chỉnh sửa Học phần</h1>
        <p class="text-slate-500 font-medium">Cập nhật thông tin cho môn học <span class="text-blue-600 font-bold">#{{ $course->code }}</span></p>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.courses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 font-bold hover:bg-white hover:text-blue-600 transition">
            &larr; Quay lại
        </a>

        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Cảnh báo: Hành động này không thể hoàn tác!\nBạn có chắc chắn muốn xóa học phần này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-50 text-red-600 font-bold hover:bg-red-600 hover:text-white transition">
                Xóa Học phần
            </button>
        </form>
    </div>
</div>

<form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Tên Học Phần</label>
                        <input type="text" name="title" value="{{ old('title', $course->title) }}"
                            class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition">
                        @error('title') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Mã Học Phần</label>
                            <input type="text" name="code" value="{{ old('code', $course->code) }}"
                                class="w-full rounded-xl border-slate-200 font-bold text-slate-700 uppercase focus:border-blue-500 focus:ring-blue-500 transition">
                            @error('code') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Dành cho lớp</label>
                            <select name="classroom_id" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition select2-enable">
                                <option value="">-- Môn chung (Không chọn lớp) --</option>
                                @foreach($classrooms as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('classroom_id', $course->classroom_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} ({{ $class->code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Mô tả đề cương</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition">{{ old('description', $course->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-4 border-b border-slate-100">
                    <label class="block text-sm font-black text-slate-700 uppercase">Nội dung chi tiết bài giảng</label>
                </div>
                <div class="p-2">
                    <textarea name="content" id="editor">{{ old('content', $course->content) }}</textarea>
                </div>
            </div>
        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                <h3 class="font-black text-slate-800 mb-4 uppercase text-sm">Thiết lập</h3>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Giảng viên phụ trách</label>
                    <select name="user_id" class="w-full rounded-xl border-slate-200 font-bold focus:border-purple-500 focus:ring-purple-500 transition select2-enable">
                        @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}"
                            {{ old('user_id', $course->user_id) == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }} ({{ $instructor->email }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Trạng thái lớp</label>
                    <select name="status" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition">
                        <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="open" {{ old('status', $course->status) == 'open' ? 'selected' : '' }}>Đang mở lớp</option>
                        <option value="closed" {{ old('status', $course->status) == 'closed' ? 'selected' : '' }}>Đã kết thúc</option>
                    </select>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="text-[10px] text-slate-400 font-bold bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p>Ngày tạo: {{ $course->created_at->format('H:i d/m/Y') }}</p>
                        <p>Cập nhật lần cuối: {{ $course->updated_at->format('H:i d/m/Y') }}</p>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 text-white font-black hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-1">
                        CẬP NHẬT THAY ĐỔI
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                <h3 class="font-black text-slate-800 mb-4 uppercase text-sm">Ảnh bìa</h3>

                <div class="relative w-full h-48 bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400 hover:bg-blue-50 hover:border-blue-300 transition cursor-pointer group overflow-hidden">
                    <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this)">

                    <div id="upload-placeholder" class="flex flex-col items-center {{ $course->image ? 'hidden' : '' }}">
                        <svg class="w-10 h-10 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs font-bold uppercase">Thay đổi ảnh</span>
                    </div>

                    <img id="image-preview"
                        src="{{ $course->image ? asset('storage/'.$course->image) : '#' }}"
                        class="absolute inset-0 w-full h-full object-cover {{ $course->image ? '' : 'hidden' }}">
                </div>
                @error('image') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-enable').select2({
            width: '100%',
            placeholder: "Nhập để tìm kiếm...",
            allowClear: true
        });

        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', 'undo', 'redo'
                ]
            })
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.addClass('ck-content', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.error(error);
            });
    });

    $(document).ready(function() {
        $('.select2-enable').select2({
            width: '100%',
            placeholder: "Nhập để tìm kiếm...",
            allowClear: true
        });
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection