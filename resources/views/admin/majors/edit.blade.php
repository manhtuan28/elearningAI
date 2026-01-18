<title>E-Learning AI - Chỉnh sửa chuyên ngành/title>
@extends('layouts.admin')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-black text-slate-800 uppercase">Chỉnh Sửa Ngành</h1>
            <a href="{{ route('admin.majors.index') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">&larr; Quay lại</a>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('admin.majors.update', $major->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label class="block text-sm font-black uppercase text-slate-600 mb-2">Tên Chuyên Ngành</label>
                    <input type="text" name="name" value="{{ old('name', $major->name) }}" 
                           class="w-full rounded-xl border-slate-200 px-4 py-3 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition shadow-sm">
                    @error('name') 
                        <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> 
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Slug hiện tại (Tự động cập nhật)</label>
                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 font-mono text-sm">
                        {{ $major->slug }}
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 py-4 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all">
                        CẬP NHẬT
                    </button>
                    
                    <a href="{{ route('admin.majors.index') }}" class="px-6 py-4 bg-slate-100 text-slate-500 font-black rounded-xl hover:bg-slate-200 transition">
                        HỦY
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection