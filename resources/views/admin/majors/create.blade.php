<title>E-Learning AI - Tạo chuyên ngành/title>
@extends('layouts.admin')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-black text-slate-800 uppercase">Thêm Ngành Mới</h1>
            <a href="{{ route('admin.majors.index') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">&larr; Quay lại</a>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('admin.majors.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-black uppercase text-slate-600 mb-2">Tên Chuyên Ngành</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Công nghệ thông tin..." 
                           class="w-full rounded-xl border-slate-200 px-4 py-3 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition shadow-sm">
                    @error('name') 
                        <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> 
                    @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all">
                    LƯU DỮ LIỆU
                </button>
            </form>
        </div>
    </div>
@endsection