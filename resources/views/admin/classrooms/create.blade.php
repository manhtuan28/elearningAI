<title>E-Learning AI - Tạo lớp học</title>
@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-black text-slate-800 uppercase">Thêm Lớp Học</h1>
            <a href="{{ route('admin.classrooms.index') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">&larr; Quay lại</a>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('admin.classrooms.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-black uppercase text-slate-600 mb-2">Mã Lớp</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="VD: CNTT15A" 
                               class="w-full rounded-xl border-slate-200 font-bold uppercase text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition">
                        @error('code') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black uppercase text-slate-600 mb-2">Tên Lớp Đầy Đủ</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="VD: Đại học CNTT K15A" 
                               class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition">
                        @error('name') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-black uppercase text-slate-600 mb-2">Thuộc Chuyên Ngành</label>
                    <div class="relative">
                        <select name="major_id" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition appearance-none cursor-pointer">
                            <option value="">-- Chọn chuyên ngành --</option>
                            @foreach($majors as $major)
                                <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                                    {{ $major->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('major_id') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all">
                    TẠO LỚP HỌC
                </button>
            </form>
        </div>
    </div>
@endsection