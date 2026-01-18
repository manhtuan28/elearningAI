<title>E-Learning AI - Chỉnh Sửa lớp học/title>
@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-black text-slate-800 uppercase">Sửa Lớp Học</h1>
            <a href="{{ route('admin.classrooms.index') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">&larr; Quay lại</a>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('admin.classrooms.update', $classroom->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-black uppercase text-slate-600 mb-2">Mã Lớp</label>
                        <input type="text" name="code" value="{{ old('code', $classroom->code) }}" 
                               class="w-full rounded-xl border-slate-200 font-bold uppercase text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition">
                        @error('code') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black uppercase text-slate-600 mb-2">Tên Lớp</label>
                        <input type="text" name="name" value="{{ old('name', $classroom->name) }}" 
                               class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition">
                        @error('name') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-black uppercase text-slate-600 mb-2">Thuộc Chuyên Ngành</label>
                    <div class="relative">
                        <select name="major_id" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition appearance-none cursor-pointer">
                            @foreach($majors as $major)
                                <option value="{{ $major->id }}" {{ old('major_id', $classroom->major_id) == $major->id ? 'selected' : '' }}>
                                    {{ $major->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 py-4 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all">
                        CẬP NHẬT
                    </button>
                    <a href="{{ route('admin.classrooms.index') }}" class="px-6 py-4 bg-slate-100 text-slate-500 font-black rounded-xl hover:bg-slate-200 transition">
                        HỦY
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection