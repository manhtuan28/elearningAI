<title>E-Learning AI - Quản lý lớp học/title>
@extends('layouts.admin')

@section('content')
    <div class="flex flex-col gap-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Quản lý Lớp học</h1>
                <p class="text-slate-500 font-medium">Danh sách các lớp hành chính và sĩ số.</p>
            </div>
            
            <a href="{{ route('admin.classrooms.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Thêm Lớp Mới
            </a>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <form action="{{ route('admin.classrooms.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                
                <div class="flex-1 relative">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập mã lớp hoặc tên lớp..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition">
                </div>

                <div class="w-full md:w-64">
                    <select name="major_id" onchange="this.form.submit()" class="w-full py-2.5 px-4 rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition cursor-pointer">
                        <option value="all">-- Tất cả chuyên ngành --</option>
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}" {{ request('major_id') == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('admin.classrooms.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-500 font-bold hover:bg-slate-200 transition text-center flex items-center justify-center" title="Xóa bộ lọc">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </a>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 font-bold flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs tracking-wider">Mã Lớp</th>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs tracking-wider">Tên Lớp</th>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs tracking-wider">Chuyên Ngành</th>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs tracking-wider">Sĩ số</th>
                        <th class="p-6 text-right font-black text-slate-500 uppercase text-xs tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classrooms as $class)
                    <tr class="group hover:bg-blue-50/40 transition duration-200">
                        <td class="p-6">
                            <span class="inline-block font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 shadow-sm group-hover:bg-white group-hover:border-blue-200 transition">
                                {{ $class->code }}
                            </span>
                        </td>
                        
                        <td class="p-6">
                            <h4 class="font-bold text-slate-700 text-sm">{{ $class->name }}</h4>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Created: {{ $class->created_at->format('d/m/Y') }}</span>
                        </td>
                        
                        <td class="p-6">
                            @if($class->major)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-bold border border-purple-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                    {{ $class->major->name }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs italic bg-slate-100 px-2 py-1 rounded">Chưa phân ngành</span>
                            @endif
                        </td>
                        
                        <td class="p-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <span class="font-bold text-slate-700">{{ $class->students_count }}</span>
                                <span class="text-xs text-slate-400 font-bold">Sinh viên</span>
                            </div>
                        </td>
                        
                        <td class="p-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.classrooms.show', $class->id) }}" class="group/btn relative p-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-600 hover:text-white transition shadow-sm border border-purple-100" title="Quản lý Sinh viên">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover/btn:opacity-100 transition whitespace-nowrap pointer-events-none">Thêm SV</span>
                                </a>

                                <a href="{{ route('admin.classrooms.edit', $class->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm border border-blue-100" title="Chỉnh sửa thông tin">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <form action="{{ route('admin.classrooms.destroy', $class->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa lớp học này?\n\n- Tên lớp: {{ $class->name }}\n- Mã lớp: {{ $class->code }}\n\nLưu ý: {{ $class->students_count }} sinh viên trong lớp sẽ trở thành sinh viên tự do (không có lớp).');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition shadow-sm border border-red-100" title="Xóa lớp học">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <p class="font-bold text-lg">Không tìm thấy lớp học nào.</p>
                                <p class="text-sm">Hãy thử thay đổi bộ lọc hoặc thêm lớp mới.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $classrooms->appends(request()->all())->links() }}
        </div>
    </div>
@endsection