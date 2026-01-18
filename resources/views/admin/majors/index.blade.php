<title>E-Learning AI - Quản lý chuyên ngành/title>
@extends('layouts.admin')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Quản lý Chuyên ngành</h1>
            <p class="text-slate-500 font-medium">Danh mục đào tạo chính của nhà trường.</p>
        </div>
        <a href="{{ route('admin.majors.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Thêm Ngành Mới
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 font-bold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs tracking-wider">Tên Chuyên Ngành</th>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs tracking-wider">Mã Slug (URL)</th>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs tracking-wider">Quy mô</th>
                        <th class="p-6 text-right font-black text-slate-500 uppercase text-xs tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($majors as $major)
                    <tr class="hover:bg-blue-50/30 transition duration-200">
                        <td class="p-6">
                            <div class="font-bold text-slate-800 text-base">{{ $major->name }}</div>
                            <span class="text-xs text-slate-400">ID: #{{ $major->id }}</span>
                        </td>
                        <td class="p-6">
                            <code class="px-2 py-1 bg-slate-100 rounded text-xs font-mono text-slate-600 border border-slate-200">
                                {{ $major->slug }}
                            </code>
                        </td>
                        <td class="p-6">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">
                                {{ $major->classrooms_count }} Lớp học
                            </span>
                        </td>
                        <td class="p-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.majors.edit', $major->id) }}" class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-600 hover:text-white transition" title="Chỉnh sửa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <form action="{{ route('admin.majors.destroy', $major->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chuyên ngành này không?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition" title="Xóa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-400">
                            Chưa có dữ liệu chuyên ngành nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $majors->links() }}
        </div>
    </div>
@endsection