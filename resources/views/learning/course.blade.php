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
            
            @if($activeLesson && $activeLesson->type == 'video')
            <div class="bg-[#0F172A] shrink-0 w-full relative group shadow-[0_20px_50px_rgba(0,0,0,0.3)] border-b border-black">
                <div class="max-w-6xl mx-auto relative">
                    <div class="aspect-video w-full bg-black flex items-center justify-center relative overflow-hidden shadow-2xl">
                        @if($activeLesson->video_url)
                            @php
                                $videoID = '';
                                if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^\"&?/ ]{11})%i', $activeLesson->video_url, $match)) {
                                    $videoID = $match[1];
                                }
                            @endphp

                            @if($videoID)
                            <iframe class="w-full h-full"
                                src="https://www.youtube.com/embed/{{ $videoID }}?rel=0&modestbranding=1&autoplay=1&showinfo=0"
                                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                            </iframe>
                            @else
                            <video controls autoplay class="w-full h-full object-contain" controlsList="nodownload">
                                <source src="{{ $activeLesson->video_url }}" type="video/mp4">
                            </video>
                            @endif
                        @elseif($activeLesson->file_path)
                            <video id="main-player" controls autoplay class="w-full h-full object-contain" controlsList="nodownload">
                                <source src="{{ asset('storage/' . $activeLesson->file_path) }}" type="video/mp4">
                            </video>
                        @else
                            <div class="flex flex-col items-center gap-4 text-slate-600 italic py-20">
                                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center animate-pulse border border-slate-700">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Video đang được xử lý...</p>
                            </div>
                        @endif
                        <div class="absolute inset-0 pointer-events-none border-[1px] border-white/5"></div>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex-1 overflow-y-auto bg-white custom-scrollbar">
                <div class="max-w-4xl mx-auto px-6 py-10 md:px-12">
                    
                    <div class="flex justify-between items-center mb-10 pb-8 border-b border-slate-100" id="lesson-nav">
                        <button class="group px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 font-black text-[10px] uppercase tracking-widest hover:bg-white hover:text-indigo-600 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 transition-all flex items-center gap-3 disabled:opacity-20 disabled:pointer-events-none" id="btn-prev" disabled>
                            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> Bài trước
                        </button>
                        <button class="group px-5 py-2.5 rounded-xl bg-slate-900 text-white font-black text-[10px] uppercase tracking-widest hover:bg-black hover:shadow-2xl hover:shadow-slate-300/50 transition-all flex items-center gap-3 disabled:opacity-20 disabled:pointer-events-none" id="btn-next" disabled>
                            Tiếp tục <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    @if($activeLesson)
                        <div class="mb-12">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-widest border border-indigo-100 shadow-sm">
                                    {{ $activeLesson->type }}
                                </span>
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic tracking-tighter">Bài học {{ $activeLesson->sort_order }}</span>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight tracking-tight italic">{{ $activeLesson->title }}</h2>
                            <div class="h-1 w-20 bg-indigo-500 mt-6 rounded-full opacity-50"></div>
                        </div>

                        @if($activeLesson->type == 'quiz')
                            @php $quizData = json_decode($activeLesson->content, true); @endphp
                            @if(is_array($quizData) && count($quizData) > 0)
                            <div class="space-y-8" x-data="{ answers: {} }">
                                @foreach($quizData as $index => $q)
                                <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-500 relative overflow-hidden group">
                                    <div class="relative z-10">
                                        <div class="flex items-center gap-4 mb-6">
                                            <div class="shrink-0 w-10 h-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black text-sm shadow-lg group-hover:rotate-6 transition-transform">
                                                {{ $index + 1 }}
                                            </div>
                                            <h3 class="font-black text-lg text-slate-800 leading-snug">{{ $q['question'] }}</h3>
                                        </div>

                                        <div class="grid grid-cols-1 gap-3 pl-0 md:pl-14">
                                            @foreach($q['options'] as $optIndex => $option)
                                            <label class="flex items-center gap-4 p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all duration-200 group/opt"
                                                :class="answers[{{ $index }}] == {{ $optIndex }} ? 'bg-indigo-50 border-indigo-300 ring-1 ring-indigo-500/20' : ''">
                                                <div class="w-6 h-6 rounded-xl border-2 border-slate-200 flex items-center justify-center group-hover/opt:border-indigo-400 transition"
                                                    :class="answers[{{ $index }}] == {{ $optIndex }} ? 'bg-indigo-600 border-indigo-600' : ''">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="answers[{{ $index }}] == {{ $optIndex }}"></div>
                                                </div>
                                                <input type="radio" name="question_{{ $index }}" value="{{ $optIndex }}" class="hidden" @click="answers[{{ $index }}] = {{ $optIndex }}">
                                                <span class="text-slate-600 font-bold text-sm transition-colors" :class="answers[{{ $index }}] == {{ $optIndex }} ? 'text-indigo-900' : ''">{{ $option }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <div class="flex justify-center pt-10">
                                    <button class="bg-indigo-600 text-white px-12 py-4 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-indigo-700 transition-all shadow-2xl shadow-indigo-200 hover:-translate-y-1 transform">
                                        Nộp bài kiểm tra ngay
                                    </button>
                                </div>
                            </div>
                            @endif
                        @else
                            @if($activeLesson->file_path && !Str::endsWith($activeLesson->file_path, ['.mp4', '.mov']))
                            <div class="mb-10 p-6 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 flex items-center justify-between group hover:border-indigo-300 hover:bg-indigo-50/30 transition-all">
                                <div class="flex items-center gap-4 text-center md:text-left">
                                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm text-indigo-600 border border-slate-100 group-hover:rotate-6 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-800 text-sm uppercase tracking-tight">Học liệu bài giảng</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Download PDF/DOCX ({{ strtoupper(pathinfo($activeLesson->file_path, PATHINFO_EXTENSION)) }})</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/'.$activeLesson->file_path) }}" target="_blank" class="px-6 py-3 bg-white text-slate-900 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-md">
                                    Tải về
                                </a>
                            </div>
                            @endif

                            <article class="ck-content prose prose-slate prose-lg max-w-none prose-p:leading-relaxed prose-headings:font-black prose-headings:tracking-tight prose-img:rounded-[2.5rem] prose-blockquote:bg-indigo-50 prose-blockquote:border-indigo-500 prose-blockquote:rounded-2xl">
                                {!! $activeLesson->content !!}
                            </article>
                        @endif

                    @else
                        <div class="py-32 text-center flex flex-col items-center justify-center gap-6">
                            <div class="w-20 h-20 bg-slate-100 rounded-[2rem] flex items-center justify-center text-slate-200 shadow-inner">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <p class="text-slate-400 font-black uppercase tracking-[0.3em] text-[10px] italic">Vui lòng chọn bài học từ danh sách</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <aside class="w-full md:w-[380px] bg-white border-l border-slate-200 flex flex-col shrink-0 absolute md:relative z-[70] h-full transition-all duration-500 ease-in-out"
             :class="sidebarOpen ? 'translate-x-0 shadow-[-40px_0_60px_-15px_rgba(0,0,0,0.1)]' : 'translate-x-full md:translate-x-0 md:w-0 opacity-0 invisible overflow-hidden'">

            <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between shrink-0">
                <h3 class="font-black text-slate-900 uppercase text-[11px] tracking-[0.2em] italic">Danh mục bài học</h3>
                <button @click="sidebarOpen = false" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
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
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
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
                                            <svg class="w-3 h-3 pl-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    @else
                                        <div class="w-6 h-6 rounded-lg bg-white border border-slate-200 text-slate-300 flex items-center justify-center group-hover:border-indigo-400 group-hover:text-indigo-400 transition-all shadow-sm">
                                            @if($lesson->type == 'video') <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            @elseif($lesson->type == 'quiz') <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @else <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> @endif
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
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E2E8F0; border-radius: 20px; transition: background 0.3s; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #CBD5E1; }

    .ck-content { color: #334155; line-height: 1.8; }
    .ck-content h1, .ck-content h2, .ck-content h3 { font-weight: 900 !important; color: #0F172A !important; margin-top: 2.5rem !important; margin-bottom: 1.25rem !important; letter-spacing: -0.025em !important; }
    .ck-content ul { list-style-type: disc !important; padding-left: 1.5rem !important; margin-bottom: 1.5rem !important; }
    .ck-content ol { list-style-type: decimal !important; padding-left: 1.5rem !important; margin-bottom: 1.5rem !important; }
    .ck-content li { margin-bottom: 0.5rem !important; }
    .ck-content table { border-collapse: collapse !important; width: 100% !important; margin: 2rem 0 !important; border: 1px solid #E2E8F0 !important; border-radius: 1rem !important; overflow: hidden !important; }
    .ck-content table td, .ck-content table th { border: 1px solid #E2E8F0 !important; padding: 1rem !important; }
    .ck-content table th { background: #F8FAFC !important; font-weight: 800 !important; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
    .ck-content blockquote { font-style: italic; border-left: 4px solid #6366F1 !important; padding: 1.5rem !important; background: #F5F3FF !important; border-radius: 0 1rem 1rem 0 !important; margin: 2rem 0 !important; }
    .ck-content img { border-radius: 2rem; shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); }
</style>
@endsection