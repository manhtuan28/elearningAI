@extends('layouts.learning')

@section('title', $activeLesson ? $activeLesson->title : $course->title)

@section('content')
<div class="h-screen flex flex-col bg-[#F8FAFC] overflow-hidden" x-data="{ sidebarOpen: true }">

    <header class="h-16 bg-slate-900/95 backdrop-blur-md text-white flex items-center justify-between px-4 md:px-6 shadow-2xl z-[60] shrink-0 relative border-b border-white/5">
        <div class="flex items-center gap-4 overflow-hidden">
            <a href="{{ route('dashboard') }}" class="group p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-300 border border-white/5 flex items-center justify-center" title="Về trang chủ">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-white group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div class="border-l border-white/10 pl-4 flex flex-col justify-center overflow-hidden">
                <h1 class="font-black text-sm md:text-base leading-tight truncate text-slate-100 uppercase tracking-wide italic">
                    {{ $course->title }}
                </h1>
                @if($activeLesson)
                <div class="flex items-center gap-2 mt-0.5">
                    <div class="flex h-1.5 w-1.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                    </div>
                    <p class="text-[10px] md:text-[11px] text-slate-400 font-bold tracking-wider truncate uppercase">Đang học: {{ $activeLesson->title }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4 shrink-0">
            <div class="hidden sm:flex items-center gap-3 pr-2">
                <div class="flex flex-col items-end">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500">Tiến độ</span>
                    @php
                    $total = $course->chapters->sum(fn($c) => $c->lessons->count());
                    $percent = $total > 0 ? round((1 / $total) * 100) : 0;
                    @endphp
                    <span class="text-xs font-black text-emerald-400 italic">{{ $percent }}%</span>
                </div>
                <div class="relative w-10 h-10">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="16" fill="none" class="text-white/5" stroke="currentColor" stroke-width="4"></circle>
                        <circle cx="18" cy="18" r="16" fill="none" class="text-emerald-500 transition-all duration-1000"
                            stroke="currentColor" stroke-width="4" stroke-dasharray="{{ $percent }}, 100" stroke-linecap="round"></circle>
                    </svg>
                </div>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-lg shadow-indigo-900/20 border border-indigo-400/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">

        <main class="flex-1 flex flex-col bg-slate-50 overflow-hidden relative z-0">

            {{-- VIDEO THEATER MODE (Giữ nguyên phần này của bạn) --}}
            @if($activeLesson && $activeLesson->type == 'video')
            <div class="bg-[#0F172A] shrink-0 w-full relative group shadow-[0_20px_50px_rgba(0,0,0,0.3)] border-b border-black">
                <div class="max-w-6xl mx-auto relative">
                    <div class="aspect-video w-full bg-black flex items-center justify-center relative overflow-hidden shadow-2xl">
                        @if($activeLesson->video_url)
                        {{-- Youtube Logic --}}
                        @php
                        $videoID = '';
                        if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^\"&?/ ]{11})%i', $activeLesson->video_url, $match)) {
                        $videoID = $match[1];
                        }
                        @endphp
                        @if($videoID)
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoID }}?rel=0&modestbranding=1&showinfo=0" frameborder="0" allowfullscreen></iframe>
                        @else
                        <video controls class="w-full h-full">
                            <source src="{{ $activeLesson->video_url }}">
                        </video>
                        @endif
                        @elseif($activeLesson->file_path)
                        <video id="main-player" controls class="w-full h-full">
                            <source src="{{ asset('storage/' . $activeLesson->file_path) }}">
                        </video>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="flex-1 overflow-y-auto bg-white custom-scrollbar">
                <div class="max-w-4xl mx-auto px-6 py-10 md:px-12">

                    {{-- Navigation Buttons (Giữ nguyên) --}}
                    <div class="flex justify-between items-center mb-10 pb-8 border-b border-slate-100">
                        <button class="group px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 font-black text-[10px] uppercase tracking-widest hover:bg-white hover:text-indigo-600 hover:shadow-xl transition-all flex items-center gap-3 disabled:opacity-20" id="btn-prev" disabled>Bài trước</button>
                        <button class="group px-5 py-2.5 rounded-xl bg-slate-900 text-white font-black text-[10px] uppercase tracking-widest hover:bg-black hover:shadow-2xl transition-all flex items-center gap-3 disabled:opacity-20" id="btn-next" disabled>Tiếp tục</button>
                    </div>

                    @if($activeLesson)
                    <div class="mb-12">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-widest border border-indigo-100 shadow-sm">{{ $activeLesson->type }}</span>
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Bài học {{ $activeLesson->sort_order }}</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight italic">{{ $activeLesson->title }}</h2>
                        <div class="h-1 w-20 bg-indigo-500 mt-6 rounded-full opacity-50"></div>
                    </div>

                    {{-- ===================== LOGIC QUIZ (NÂNG CẤP) ===================== --}}
                    @if($activeLesson->type == 'quiz')
                    @php
                    $quizData = json_decode($activeLesson->content, true);
                    // Lấy thông tin bài làm cũ từ controller truyền sang view (biến $submission)
                    $lastScore = $submission ? $submission->score : null;
                    $attempts = $submission ? ($submission->attempt_count ?? 1) : 0;
                    $hasSubmitted = $submission ? true : false;
                    @endphp

                    @if(is_array($quizData) && count($quizData) > 0)
                    <div x-data="{ 
                        answers: {}, 
                        submitted: {{ $hasSubmitted ? 'true' : 'false' }},
                        score: {{ $lastScore !== null ? $lastScore : 'null' }},
                        attempts: {{ $attempts }},
                        isSubmitting: false,
                        newHistories: [],
                        
                        async submitQuiz() {
                            if (Object.keys(this.answers).length < {{ count($quizData) }}) {
                                if(!confirm('Bạn chưa làm hết câu hỏi. Vẫn muốn nộp?')) return;
                            }
                            this.isSubmitting = true;
                            try {
                                const res = await axios.post(`/learning/lessons/{{ $activeLesson->id }}/submit`, { submission_content: this.answers });
                                this.score = res.data.score;
                                this.attempts = res.data.attempts;
                                if(res.data.histories && res.data.histories.length > 0) {
                                    this.newHistories = [res.data.histories[0]]; 
                                }
                                this.submitted = true;
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            } catch (e) { alert('Lỗi: ' + e.message); } 
                            finally { this.isSubmitting = false; }
                        }
                    }">
                        <div x-show="submitted" x-transition class="mb-10 p-8 rounded-[2rem] text-center border-2 border-dashed"
                            :class="score >= 5 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200'">

                            <p class="text-xs font-black uppercase tracking-widest mb-2"
                                :class="score >= 5 ? 'text-emerald-500' : 'text-red-500'"
                                x-text="score >= 5 ? '🎉 CHÚC MỪNG! BẠN ĐÃ HOÀN THÀNH' : '⚠️ CẦN CỐ GẮNG HƠN'"></p>

                            <div class="text-6xl font-black mb-2" :class="score >= 5 ? 'text-emerald-600' : 'text-red-600'">
                                <span x-text="score"></span><span class="text-2xl opacity-50">/10</span>
                            </div>

                            <div class="flex justify-center gap-4 text-xs font-bold opacity-70 mb-6">
                                <span>Lần làm: <span x-text="attempts"></span></span>
                                <span>•</span>
                                <span>Trạng thái: Đã chấm</span>
                            </div>

                            <button @click="submitted = false; answers = {}; window.scrollTo({ top: 0, behavior: 'smooth' })"
                                class="px-6 py-3 bg-white rounded-xl shadow-lg font-black text-xs uppercase tracking-widest hover:scale-105 transition transform"
                                :class="score >= 5 ? 'text-emerald-600' : 'text-red-600'">
                                ↺ Làm lại bài
                            </button>
                            <div class="mt-8 border-t border-slate-100 pt-6">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Lịch sử làm bài</h4>

                                <div class="space-y-3">
                                    @if($submission && $submission->histories->count() > 0)
                                    @foreach($submission->histories as $hist)
                                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                                        <div class="flex items-center gap-3">
                                            <span class="font-bold text-slate-700 bg-white px-2 py-1 rounded border border-slate-200 shadow-sm">
                                                Lần {{ $hist->attempt_number }}
                                            </span>
                                            <span class="text-slate-400 italic">{{ $hist->submitted_at->diffForHumans() }}</span>
                                        </div>
                                        <span class="font-black {{ $hist->score >= 5 ? 'text-emerald-600' : 'text-red-500' }}">
                                            {{ $hist->score }}/10
                                        </span>
                                    </div>
                                    @endforeach
                                    @endif

                                    <template x-for="h in newHistories">
                                        <div class="flex justify-between items-center p-3 bg-white border-2 border-indigo-50 rounded-xl text-xs shadow-sm animate-pulse">
                                            <div class="flex items-center gap-3">
                                                <span class="font-bold text-indigo-700 bg-indigo-50 px-2 py-1 rounded">
                                                    Lần <span x-text="h.attempt"></span> (Mới)
                                                </span>
                                                <span class="text-slate-400 italic">Vừa xong</span>
                                            </div>
                                            <span class="font-black text-indigo-600" x-text="h.score + '/10'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-show="!submitted" class="space-y-8">
                            @foreach($quizData as $index => $q)
                            <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm group hover:border-indigo-100 transition">
                                <h3 class="font-black text-lg text-slate-800 mb-6 flex gap-3">
                                    <span class="shrink-0 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-sm">{{ $index + 1 }}</span>
                                    {{ $q['question'] }}
                                </h3>
                                <div class="grid gap-3 pl-11">
                                    @foreach($q['options'] as $optIndex => $option)
                                    <label class="flex items-center gap-4 p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition"
                                        :class="answers[{{ $index }}] == {{ $optIndex }} ? 'bg-indigo-50 border-indigo-200 ring-1 ring-indigo-100' : ''">
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                            :class="answers[{{ $index }}] == {{ $optIndex }} ? 'border-indigo-600 bg-indigo-600' : 'border-slate-300'">
                                            <div class="w-2 h-2 bg-white rounded-full" x-show="answers[{{ $index }}] == {{ $optIndex }}"></div>
                                        </div>
                                        <input type="radio" x-model="answers[{{ $index }}]" value="{{ $optIndex }}" class="hidden">
                                        <span class="text-sm font-bold text-slate-600">{{ $option }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach

                            <div class="flex justify-center pt-8">
                                <button @click="submitQuiz" :disabled="isSubmitting"
                                    class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition disabled:opacity-50">
                                    <span x-text="isSubmitting ? 'ĐANG CHẤM ĐIỂM...' : 'NỘP BÀI KIỂM TRA'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-center text-slate-400 italic">Chưa có câu hỏi.</p>
                    @endif

                    @elseif($activeLesson->type == 'homework')

                    {{-- Nội dung đề bài --}}
                    <div class="prose prose-slate max-w-none mb-10">
                        {!! $activeLesson->content !!}
                    </div>

                    @if($submission && $submission->score !== null)
                    <div class="mb-8 p-6 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 rounded-[2rem] flex items-center justify-between shadow-sm animate-fade-in-down">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Kết quả chấm điểm</span>
                            </div>
                            <p class="text-sm font-medium text-emerald-800">Giảng viên đã chấm bài làm của bạn.</p>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-black text-emerald-600 tracking-tighter">{{ $submission->score }}</span>
                            <span class="text-lg font-bold text-emerald-400">/10</span>
                        </div>
                    </div>
                    @endif

                    {{-- Khu vực nộp bài --}}
                    <div class="border-t-2 border-dashed border-slate-200 pt-10" x-data="{
                        isUploading: false,
                        file: null,
                        uploadedName: '{{ $submission && $submission->submission_content ? json_decode($submission->submission_content)->file_name ?? '' : '' }}',
                        
                        async uploadHomework(e) {
                            this.file = e.target.files[0];
                            if(!this.file) return;

                            let fd = new FormData();
                            fd.append('file_upload', this.file);

                            this.isUploading = true;
                            try {
                                const res = await axios.post(`/learning/lessons/{{ $activeLesson->id }}/submit`, fd, {
                                    headers: { 'Content-Type': 'multipart/form-data' }
                                });
                                this.uploadedName = this.file.name;
                                alert('Nộp bài tập thành công!');
                            } catch(err) {
                                alert('Lỗi upload: ' + (err.response?.data?.message || err.message));
                            } finally {
                                this.isUploading = false;
                            }
                        }
                    }">
                        <h3 class="font-black text-slate-800 uppercase text-sm tracking-widest mb-6">Khu vực nộp bài</h3>

                        <div class="relative group">
                            <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 border-dashed rounded-[2rem] cursor-pointer bg-slate-50 hover:bg-indigo-50 hover:border-indigo-400 transition-all">

                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-slate-400 group-hover:text-indigo-500 transition">
                                    <div x-show="!isUploading">
                                        <svg class="w-10 h-10 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-sm font-bold"><span class="font-black">Click để chọn file</span> hoặc kéo thả vào đây</p>
                                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">DOCX, PDF, ZIP, IMG (MAX 10MB)</p>
                                    </div>
                                    <div x-show="isUploading" class="animate-pulse font-black uppercase text-xs tracking-widest">
                                        Đang tải lên...
                                    </div>
                                </div>
                                <input type="file" class="hidden" @change="uploadHomework">
                            </label>
                        </div>

                        <div x-show="uploadedName" class="mt-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 animate-fade-in-up">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Bài đã nộp</p>
                                <p class="text-sm font-bold text-slate-700" x-text="uploadedName"></p>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== DOCUMENT (GIỮ NGUYÊN) ===================== --}}
                    @else
                    {{-- Code hiển thị tài liệu cũ của bạn giữ nguyên --}}
                    @if($activeLesson->file_path && !Str::endsWith($activeLesson->file_path, ['.mp4', '.mov']))
                    <div class="mb-10 p-6 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 flex items-center justify-between group hover:border-indigo-300 hover:bg-indigo-50/30 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm text-indigo-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg></div>
                            <div>
                                <h4 class="font-black text-slate-800 text-sm">Học liệu bài giảng</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Download File</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/'.$activeLesson->file_path) }}" download class="px-6 py-3 bg-white font-black text-[10px] uppercase rounded-xl hover:bg-indigo-600 hover:text-white transition shadow-sm">Tải về</a>
                    </div>
                    @endif
                    <div class="ck-content prose prose-slate max-w-none">{!! $activeLesson->content !!}</div>
                    @endif

                    @else
                    <div class="py-32 text-center text-slate-400 font-black uppercase text-xs">Vui lòng chọn bài học</div>
                    @endif
                </div>
            </div>
        </main>
        <aside class="w-full md:w-[380px] bg-white border-l border-slate-200 flex flex-col shrink-0 absolute md:relative z-[70] h-full transition-all duration-500 ease-in-out"
            :class="sidebarOpen ? 'translate-x-0 shadow-[-40px_0_60px_-15px_rgba(0,0,0,0.1)]' : 'translate-x-full md:translate-x-0 md:w-0 opacity-0 invisible overflow-hidden'">

            <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between shrink-0">
                <h3 class="font-black text-slate-900 uppercase text-[11px] tracking-[0.2em] italic">Danh mục bài học</h3>
                <button @click="sidebarOpen = false" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar bg-white" id="playlist">
                @foreach($course->chapters as $chapter)
                <div x-data="{ expanded: {{ $activeLesson && $activeLesson->chapter_id == $chapter->id ? 'true' : 'false' }} }" class="border-b border-slate-50">
                    <div @click="expanded = !expanded" class="p-5 bg-white hover:bg-slate-50 cursor-pointer flex justify-between items-start transition-all duration-300 group select-none sticky top-0 z-10 border-b border-transparent">
                        <div class="flex-1 pr-4">
                            <h4 class="font-black text-[11px] text-slate-800 uppercase tracking-tighter group-hover:text-indigo-600 transition leading-relaxed">{{ $chapter->title }}</h4>
                            <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase italic tracking-widest">{{ $chapter->lessons->count() }} BÀI GIẢNG</p>
                        </div>
                        <div class="w-5 h-5 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300 transition-all group-hover:bg-indigo-100 group-hover:text-indigo-600 shadow-sm" :class="expanded ? 'rotate-180' : ''">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div x-show="expanded" class="bg-slate-50/30 pb-3 space-y-1" x-collapse>
                        @foreach($chapter->lessons as $lesson)
                        @php $isActive = isset($activeLesson) && $activeLesson->id == $lesson->id; @endphp
                        <a href="{{ route('learning.course', ['id' => $course->id, 'lesson_id' => $lesson->id]) }}"
                            class="lesson-item block relative group {{ $isActive ? 'bg-indigo-50 border-l-[3px] border-indigo-600' : 'hover:bg-white border-l-[3px] border-transparent hover:border-slate-300' }} transition-all"
                            data-id="{{ $lesson->id }}">

                            <div class="p-4 pl-6 flex gap-4">
                                <div class="mt-1 shrink-0">
                                    @if($isActive)
                                    <div class="w-6 h-6 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/50 animate-pulse ring-4 ring-indigo-100">
                                        <svg class="w-3 h-3 pl-0.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-6 h-6 rounded-lg bg-white border border-slate-200 text-slate-300 flex items-center justify-center group-hover:border-indigo-400 group-hover:text-indigo-400 transition-all shadow-sm">
                                        @if($lesson->type == 'video') <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                        @elseif($lesson->type == 'quiz') <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @else <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg> @endif
                                    </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-black leading-snug uppercase tracking-tighter truncate {{ $isActive ? 'text-indigo-900' : 'text-slate-600 group-hover:text-slate-900' }} transition-colors">
                                        {{ $loop->iteration }}. {{ $lesson->title }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1.5 opacity-60">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">
                                            @if($lesson->type == 'video') VIDEO @elseif($lesson->type == 'quiz') QUIZ @else TÀI LIỆU @endif
                                            @if($lesson->duration) • {{ $lesson->duration }} PHÚT @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </aside>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentId = '{{ $activeLesson ? $activeLesson->id : "" }}';
        const links = Array.from(document.querySelectorAll('.lesson-item'));
        const currentIndex = links.findIndex(link => link.dataset.id === currentId);

        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');

        if (currentIndex > 0) {
            btnPrev.disabled = false;
            btnPrev.onclick = () => window.location.href = links[currentIndex - 1].href;
        }

        if (currentIndex < links.length - 1 && currentIndex !== -1) {
            btnNext.disabled = false;
            btnNext.onclick = () => window.location.href = links[currentIndex + 1].href;
        }

        if (currentIndex !== -1) {
            links[currentIndex].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #E2E8F0;
        border-radius: 20px;
        transition: background 0.3s;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #CBD5E1;
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
@endsection