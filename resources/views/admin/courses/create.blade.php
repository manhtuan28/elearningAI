<title>E-Learning AI - Tạo học phần</title>
@extends('layouts.admin')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
</style>

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Thêm Học Phần Mới</h1>
            <p class="text-slate-500 font-medium">Điền thông tin để mở lớp học phần mới.</p>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="text-slate-500 font-bold hover:text-blue-600 transition">
            &larr; Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Tên Học Phần <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Ví dụ: Lập trình Web với Laravel" 
                                class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition">
                            @error('title') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Mã Học Phần <span class="text-red-500">*</span></label>
                                <input type="text" name="code" value="{{ old('code') }}" placeholder="Ví dụ: INT3306" 
                                    class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 uppercase transition">
                                @error('code') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Dành cho lớp</label>
                                <select name="classroom_id" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition select2-enable">
                                    <option value="">-- Môn chung (Không chọn lớp) --</option>
                                    @foreach($classrooms as $class)
                                        <option value="{{ $class->id }}" {{ old('classroom_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ $class->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2 uppercase">Mô tả đề cương</label>
                            <textarea name="description" rows="3" placeholder="Tóm tắt nội dung học phần..." 
                                class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <label class="block text-sm font-black text-slate-700 mb-4 uppercase">Nội dung chi tiết</label>
                    <textarea name="content" rows="10" 
                        class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition"
                        placeholder="Nhập chi tiết giáo trình, yêu cầu môn học...">{{ old('content') }}</textarea>
                </div>
            </div>

            <div class="space-y-6">
                
                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <h3 class="font-black text-slate-800 mb-4 uppercase text-sm">Thiết lập lớp học</h3>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Giảng viên phụ trách <span class="text-red-500">*</span></label>
                        <select name="user_id" class="w-full rounded-xl border-slate-200 font-bold focus:border-purple-500 focus:ring-purple-500 transition select2-enable">
                            <option value="">-- Tìm kiếm giảng viên --</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('user_id') == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->name }} ({{ $instructor->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Trạng thái</label>
                        <select name="status" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500 transition">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                            <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Đang mở lớp (Open)</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Đã kết thúc (Closed)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 text-white font-black hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-1">
                        LƯU HỌC PHẦN
                    </button>
                </div>

                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <h3 class="font-black text-slate-800 mb-4 uppercase text-sm">Ảnh bìa môn học</h3>
                    
                    <div class="relative w-full h-48 bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400 hover:bg-blue-50 hover:border-blue-300 transition cursor-pointer group overflow-hidden">
                        <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                        
                        <div id="upload-placeholder" class="flex flex-col items-center">
                            <svg class="w-10 h-10 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-bold uppercase">Tải ảnh lên</span>
                        </div>
                        
                        <img id="image-preview" src="#" class="absolute inset-0 w-full h-full object-cover hidden">
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