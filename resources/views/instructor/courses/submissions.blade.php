<title>Kết quả: {{ $lesson->title }}</title>
@extends('layouts.admin')

<script src="//unpkg.com/alpinejs" defer></script>

@section('content')
<div x-data="{ 
    modalOpen: false, 
    selectedSub: null,
    gradingScore: '', 
    isGrading: false,
    // Lấy dữ liệu câu hỏi gốc để so sánh đáp án (Chỉ dùng cho Quiz)
    quizData: {{ $lesson->type === 'quiz' && isset($quizData) ? json_encode($quizData) : 'null' }},

    // Hàm mở modal và reset điểm (quan trọng cho chức năng chấm điểm)
    openModal(sub) {
        this.selectedSub = sub;
        this.gradingScore = sub.score !== null ? sub.score : '';
        this.modalOpen = true;
    },

    // Hàm gọi API chấm điểm (Dành cho bài tập tự luận)
    async submitGrade() {
        if (this.gradingScore === '' || this.gradingScore < 0 || this.gradingScore > 10) {
            alert('Vui lòng nhập điểm hợp lệ (0-10)'); return;
        }
        this.isGrading = true;
        try {
            await axios.post(`/instructor/submissions/${this.selectedSub.id}/grade`, {
                score: this.gradingScore
            });
            alert('Đã lưu điểm số!');
            location.reload();
        } catch (e) {
            alert('Lỗi: ' + (e.response?.data?.message || e.message));
        } finally {
            this.isGrading = false;
        }
    },

    // Hàm Render Quiz: Xanh/Đỏ
    renderQuizResult(submissionContent) {
        try {
            const userAnswers = JSON.parse(submissionContent);
            if (!this.quizData) return 'Không tìm thấy dữ liệu câu hỏi gốc.';

            let html = '<div class=\'space-y-4\'>';
            
            this.quizData.forEach((q, index) => {
                const userChoice = userAnswers[index];
                const isCorrect = userChoice == q.correct;
                
                // Style cho câu đúng/sai
                const boxClass = isCorrect ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50';
                const textClass = isCorrect ? 'text-emerald-700' : 'text-red-700';
                const icon = isCorrect ? '✅' : '❌';

                // Lấy nội dung đáp án sinh viên chọn (nếu có chọn)
                const userChoiceText = (userChoice !== undefined && q.options[userChoice]) 
                                        ? q.options[userChoice] 
                                        : '<span class=\'italic text-slate-400\'>Không trả lời</span>';
                
                const correctText = q.options[q.correct];

                html += `
                    <div class='p-4 rounded-xl border ${boxClass}'>
                        <div class='flex justify-between items-start mb-2'>
                            <span class='font-bold text-slate-700 text-xs uppercase'>Câu ${index + 1}</span>
                            <span>${icon}</span>
                        </div>
                        <p class='font-bold text-slate-800 text-sm mb-2'>${q.question}</p>
                        
                        <div class='text-xs space-y-1'>
                            <div class='flex gap-2'>
                                <span class='font-bold text-slate-500'>Đã chọn:</span>
                                <span class='font-bold ${textClass}'>${userChoiceText}</span>
                            </div>
                            ${!isCorrect ? `
                            <div class='flex gap-2'>
                                <span class='font-bold text-slate-500'>Đáp án đúng:</span>
                                <span class='font-bold text-emerald-600'>${correctText}</span>
                            </div>` : ''}
                        </div>
                    </div>`;
            });
            
            html += '</div>';
            return html;
        } catch (e) {
            return 'Lỗi hiển thị: ' + e.message;
        }
    },

    // Hàm Render Homework: Hiển thị File
    renderHomework(submissionContent) {
        try {
            const data = JSON.parse(submissionContent);
            
            // Kiểm tra nếu là file upload
            if (data.file_path) {
                const fileExt = data.file_name.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt);
                const downloadUrl = `/storage/${data.file_path}`;

                return `
                    <div class='text-center p-6 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 mb-6'>
                        <div class='mb-4'>
                            ${isImage ? 
                                `<img src='${downloadUrl}' class='max-h-64 mx-auto rounded-lg shadow-sm border border-slate-100'>` : 
                                `<div class='w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto text-indigo-500 shadow-sm border border-slate-100 mb-2'>
                                    <svg class='w-8 h-8' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'></path></svg>
                                </div>`
                            }
                        </div>
                        <p class='font-bold text-slate-800 text-sm mb-1'>${data.file_name}</p>
                        <p class='text-xs text-slate-400 mb-4'>Nộp lúc: ${data.submitted_at || 'N/A'}</p>
                        
                        <a href='${downloadUrl}' download class='inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase hover:bg-indigo-700 transition shadow-lg shadow-indigo-200'>
                            <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'></path></svg>
                            Tải bài làm về
                        </a>
                    </div>
                `;
            }
            
            // Fallback cho text cũ (nếu có)
            return `<div class='bg-slate-50 p-4 rounded-xl text-sm text-slate-600'>${submissionContent}</div>`;
        } catch (e) {
            return `<div class='bg-slate-50 p-4 rounded-xl text-sm text-slate-600'>${submissionContent}</div>`;
        }
    }
}">
    
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 text-slate-400 font-bold text-xs uppercase tracking-widest mb-2">
                <a href="{{ route('instructor.courses.manage', $lesson->chapter->course->id) }}" class="hover:text-indigo-600 transition">Giáo trình</a>
                <span>/</span>
                <span>{{ $lesson->chapter->title }}</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                {{ $lesson->title }}
            </h1>
            <div class="mt-4 flex items-center gap-4">
                <div class="px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-black uppercase tracking-widest border border-indigo-100">
                    {{ $lesson->type == 'quiz' ? 'Trắc nghiệm' : 'Bài tập tự luận' }}
                </div>
                <span class="text-sm font-bold text-slate-500">{{ $submissions->count() }} bài nộp</span>
            </div>
        </div>
        <a href="{{ route('instructor.courses.manage', $lesson->chapter->course->id) }}" 
           class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-500 hover:text-indigo-600 hover:border-indigo-100 transition shadow-sm flex items-center gap-2 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
        @if($submissions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="p-6 pl-8 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Sinh viên</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Thời gian nộp</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Trạng thái / Điểm</th>
                        <th class="p-6 pr-8 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($submissions as $sub)
                    <tr class="group hover:bg-slate-50/50 transition-colors duration-200">
                        <td class="p-6 pl-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-black text-sm shadow-md shadow-indigo-200">
                                    {{ substr($sub->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm group-hover:text-indigo-700 transition">{{ $sub->user->name }}</p>
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $sub->user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-6">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700 text-sm">{{ $sub->created_at->format('H:i - d/m/Y') }}</span>
                                <span class="text-[10px] text-slate-400 font-medium italic">{{ $sub->created_at->diffForHumans() }}</span>
                                @if($sub->attempt_count > 1)
                                    <span class="text-[9px] text-purple-500 font-bold uppercase tracking-wider mt-1">Lần làm: {{ $sub->attempt_count }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="p-6">
                            @if($sub->score !== null)
                                @php 
                                    $scoreColor = $sub->score >= 8 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                                                 ($sub->score >= 5 ? 'bg-yellow-50 text-yellow-600 border-yellow-100' : 'bg-red-50 text-red-600 border-red-100');
                                @endphp
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl border {{ $scoreColor }}">
                                    <span class="font-black text-sm">{{ $sub->score }}</span>
                                    <span class="text-[10px] opacity-70 font-bold uppercase">/ 10 ĐIỂM</span>
                                </div>
                            @else
                                <span class="px-4 py-1.5 rounded-xl bg-blue-50 text-blue-600 font-black text-[10px] uppercase tracking-wider border border-blue-100 flex w-fit items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Chờ chấm
                                </span>
                            @endif
                        </td>

                        <td class="p-6 pr-8 text-right">
                            <button @click="openModal({{ json_encode($sub) }})" 
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 font-black text-[10px] uppercase tracking-wider hover:border-indigo-200 hover:text-indigo-600 hover:shadow-lg hover:shadow-indigo-100 transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ $lesson->type == 'homework' && $sub->score === null ? 'Chấm điểm' : 'Chi tiết' }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="py-20 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-black text-slate-800">Chưa có bài nộp nào</h3>
                <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto">Hiện tại chưa có sinh viên nào hoàn thành bài học này.</p>
            </div>
        @endif
    </div>

    <div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
             x-show="modalOpen" x-transition.opacity @click="modalOpen = false"></div>

        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl relative z-10 overflow-hidden flex flex-col max-h-[90vh]"
             x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            <div class="p-8 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="font-black text-xl text-slate-800">Chi tiết bài làm</h3>
                    <p class="text-xs text-slate-500 font-bold mt-1" x-text="selectedSub?.user?.name + ' - ' + (selectedSub?.created_at ? new Date(selectedSub.created_at).toLocaleString('vi-VN') : '')"></p>
                </div>
                <button @click="modalOpen = false" class="p-2 bg-white rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar">
                <div class="mb-6">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Nội dung / File nộp</label>
                    <div x-html="selectedSub && {{ $lesson->type == 'quiz' ? 'true' : 'false' }} ? renderQuizResult(selectedSub.submission_content) : (selectedSub ? renderHomework(selectedSub.submission_content) : '')"></div>
                </div>

                @if($lesson->type == 'homework')
                <div class="p-6 bg-indigo-50 rounded-2xl border border-indigo-100 mt-6">
                    <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Chấm điểm (0-10)</label>
                    <div class="flex gap-3">
                        <input type="number" x-model="gradingScore" min="0" max="10" step="0.1" class="w-24 rounded-xl border-slate-200 font-black text-center text-xl text-indigo-700 focus:ring-indigo-500" placeholder="0">
                        <button @click="submitGrade" :disabled="isGrading" class="flex-1 bg-indigo-600 text-white rounded-xl font-black uppercase text-xs tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            <span x-text="isGrading ? 'Đang lưu...' : 'Lưu kết quả'"></span>
                        </button>
                    </div>
                </div>
                @endif

                <template x-if="selectedSub?.score !== null && {{ $lesson->type == 'quiz' ? 'true' : 'false' }}">
                    <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl border border-slate-100 mt-6">
                        <div>
                            <p class="font-black text-slate-400 uppercase text-[10px] tracking-widest mb-1">Kết quả cuối cùng</p>
                            <p class="text-xs font-bold text-slate-500">Hệ thống tự động chấm</p>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-3xl text-slate-800" x-text="selectedSub.score"></span>
                            <span class="text-sm font-bold text-slate-400">/10</span>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 shrink-0">
                <button @click="modalOpen = false" class="px-6 py-3 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-100 transition">Đóng</button>
            </div>
        </div>
    </div>

</div>
@endsection