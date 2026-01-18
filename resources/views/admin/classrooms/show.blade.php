<title>E-Learning AI - Chi tiết lớp học</title>
@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Chi tiết Lớp học</h1>
        <p class="text-slate-500 font-medium">
            Lớp: <span class="text-blue-600 font-bold">{{ $classroom->code }}</span> -
            {{ $classroom->name }}
        </p>
    </div>
    <a href="{{ route('admin.classrooms.index') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">&larr; Quay lại danh sách</a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 font-bold flex items-center gap-3 shadow-sm">
    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('warning'))
<div class="mb-6 p-4 bg-yellow-50 text-yellow-800 rounded-xl border border-yellow-200 font-bold flex items-start gap-3 shadow-sm">
    <svg class="w-6 h-6 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
    </svg>
    <div>
        {{ session('warning') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 font-bold flex items-center gap-3 shadow-sm">
    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-black text-slate-700 uppercase">Danh sách sinh viên ({{ $classroom->students->count() }})</h3>
            </div>

            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs">Họ tên</th>
                        <th class="p-6 font-black text-slate-500 uppercase text-xs">Email</th>
                        <th class="p-6 text-right font-black text-slate-500 uppercase text-xs">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classroom->students as $student)
                    <tr class="hover:bg-blue-50/30 transition">
                        <td class="p-6 font-bold text-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-black">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                {{ $student->name }}
                            </div>
                        </td>
                        <td class="p-6 text-sm text-slate-500">{{ $student->email }}</td>
                        <td class="p-6 text-right">
                            <form action="{{ route('admin.classrooms.remove_student', ['id' => $classroom->id, 'student_id' => $student->id]) }}" method="POST" onsubmit="return confirm('Xóa sinh viên này khỏi lớp?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-500 hover:underline hover:text-red-700">
                                    Đuổi khỏi lớp
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-slate-400">Lớp này chưa có sinh viên nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-6">

            <h3 class="font-black text-slate-800 uppercase mb-4">Thêm Sinh Viên</h3>

            <div x-data="{ tab: 'manual' }" class="space-y-4">
                <div class="flex p-1 bg-slate-100 rounded-xl">
                    <button @click="tab = 'manual'" :class="{ 'bg-white text-blue-600 shadow-sm': tab === 'manual', 'text-slate-500 hover:text-slate-700': tab !== 'manual' }" class="flex-1 py-2 rounded-lg text-xs font-bold transition">
                        Thêm lẻ
                    </button>
                    <button @click="tab = 'excel'" :class="{ 'bg-white text-green-600 shadow-sm': tab === 'excel', 'text-slate-500 hover:text-slate-700': tab !== 'excel' }" class="flex-1 py-2 rounded-lg text-xs font-bold transition">
                        Nhập Excel
                    </button>
                </div>

                <div x-show="tab === 'manual'">
                    <p class="text-xs text-slate-500 mb-4">Chọn sinh viên tự do từ danh sách.</p>
                    <form action="{{ route('admin.classrooms.add_student', $classroom->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <select name="student_id" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-blue-500 transition" size="8">
                                @forelse($freeStudents as $free)
                                <option value="{{ $free->id }}" class="py-2 px-2 hover:bg-blue-50 cursor-pointer rounded">
                                    {{ $free->name }}
                                </option>
                                @empty
                                <option disabled class="text-slate-400 italic p-2">Hết sinh viên tự do.</option>
                                @endforelse
                            </select>
                        </div>
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all">
                            THÊM NGAY
                        </button>
                    </form>
                </div>

                <div x-show="tab === 'excel'" style="display: none;">
                    <p class="text-xs text-slate-500 mb-2">Tải lên file Excel chứa cột <strong>email</strong>.</p>

                    <button onclick="downloadSimpleTemplate()" class="text-xs font-bold text-green-600 hover:underline mb-4 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Tải file mẫu (.csv)
                    </button>

                    <form action="{{ route('admin.classrooms.import_students', $classroom->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="relative w-full h-32 bg-green-50 rounded-xl border-2 border-dashed border-green-300 flex flex-col items-center justify-center text-green-600 hover:bg-white transition cursor-pointer group mb-4">
                            <input type="file" name="file" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="document.getElementById('excel-name').innerText = this.files[0].name" required>
                            <svg class="w-8 h-8 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="text-xs font-bold" id="excel-name">Chọn file Excel</span>
                        </div>

                        <button type="submit" class="w-full py-3 bg-green-600 text-white font-black rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 hover:-translate-y-1 transition-all">
                            UPLOAD & GÁN LỚP
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadSimpleTemplate() {
            const rows = [
                ["email"],
                ["sinhvien1@gmail.com"],
                ["sinhvien2@gmail.com"]
            ];
            let csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");
            const link = document.createElement("a");
            link.setAttribute("href", encodeURI(csvContent));
            link.setAttribute("download", "mau_them_sv_vao_lop.csv");
            document.body.appendChild(link);
            link.click();
        }
    </script>
</div>
@endsection