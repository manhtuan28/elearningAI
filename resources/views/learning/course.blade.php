@extends('layouts.learning')

@section('title', $activeLesson ? $activeLesson->title : $course->title)

@section('content')
    <div class="h-screen flex flex-col bg-slate-50 overflow-hidden" x-data="{ sidebarOpen: true }">
        
        <div class="h-16 bg-slate-900 text-white flex items-center justify-between px-4 md:px-6 shadow-md z-50 shrink-0 relative">
            <div class="flex items-center gap-4 overflow-hidden">
                <a href="{{ route('dashboard') }}" class="p-2 hover:bg-white/10 rounded-full transition text-slate-400 hover:text-white" title="Về trang chủ">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div class="border-l border-slate-700 pl-4 flex flex-col justify-center overflow-hidden">
                    <h1 class="font-bold text-sm md:text-base leading-tight truncate text-slate-100">{{ $course->title }}</h1>
                    @if($activeLesson)
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <p class="text-[10px] md:text-xs text-slate-400 font-medium truncate">Đang học: {{ $activeLesson->title }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center gap-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="hidden md:flex flex-col items-end">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Tiến độ</span>
                        @php
                            $total = $course->chapters->sum(fn($c) => $c->lessons->count());
                            $percent = $total > 0 ? round((1 / $total) * 100) : 0; 
                        @endphp
                        <span class="text-xs font-bold text-emerald-400">{{ $percent }}%</span>
                    </div>
                    <div class="relative w-9 h-9">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-800" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                            <path class="text-emerald-500 transition-all duration-1000 ease-out" stroke-dasharray="{{ $percent }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                        </svg>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </div>

        <div class="flex-1 flex overflow-hidden relative">
            
            <div class="flex-1 flex flex-col bg-white overflow-hidden relative z-0">
                
                @if($activeLesson && $activeLesson->type == 'video')
                    <div class="bg-black shrink-0 w-full relative group">
                        <div class="aspect-video w-full max-h-[70vh] mx-auto bg-black flex items-center justify-center shadow-2xl">
                            @if($activeLesson->video_url)
                                @php
                                    $videoID = '';
                                    if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $activeLesson->video_url, $match)) {
                                        $videoID = $match[1];
                                    }
                                @endphp
                                @if($videoID)
                                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoID }}?rel=0&modestbranding=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @else
                                    <video controls class="w-full h-full"><source src="{{ $activeLesson->video_url }}" type="video/mp4"></video>
                                @endif
                            @elseif($activeLesson->file_path && Str::endsWith($activeLesson->file_path, ['.mp4', '.mov']))
                                <video controls class="w-full h-full" controlsList="nodownload"><source src="{{ asset('storage/' . $activeLesson->file_path) }}" type="video/mp4"></video>
                            @else
                                <div class="text-center text-slate-500"><p>Video không khả dụng.</p></div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex-1 overflow-y-auto bg-white custom-scrollbar">
                    <div class="max-w-4xl mx-auto p-6 md:p-10">
                        
                        <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6" id="lesson-nav">
                            <button class="px-4 py-2 rounded-lg border border-slate-200 text-slate-500 font-bold text-xs hover:bg-slate-50 transition flex items-center gap-2" id="btn-prev" disabled>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> Bài trước
                            </button>
                            <button class="px-4 py-2 rounded-lg bg-slate-900 text-white font-bold text-xs hover:bg-black transition flex items-center gap-2" id="btn-next" disabled>
                                Bài tiếp theo <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>

                        @if($activeLesson)
                            <div class="mb-8">
                                <span class="px-3 py-1 rounded bg-purple-50 text-purple-700 text-[10px] font-black uppercase tracking-wider mb-3 inline-block border border-purple-100">
                                    {{ ucfirst($activeLesson->type) }}
                                </span>
                                <h2 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight">{{ $activeLesson->title }}</h2>
                            </div>

                            {{-- 1. LOẠI QUIZ (TRẮC NGHIỆM) --}}
                            @if($activeLesson->type == 'quiz')
                                @php
                                    $quizData = json_decode($activeLesson->content, true);
                                @endphp

                                @if(is_array($quizData) && count($quizData) > 0)
                                    <div class="space-y-8" x-data="{ answers: {} }">
                                        @foreach($quizData as $index => $q)
                                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition">
                                                <div class="flex gap-4">
                                                    <div class="shrink-0 w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center font-black text-sm">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <h3 class="font-bold text-lg text-slate-800 mb-4">{{ $q['question'] }}</h3>
                                                        
                                                        <div class="space-y-3">
                                                            @foreach($q['options'] as $optIndex => $option)
                                                                <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition group"
                                                                       :class="answers[{{ $index }}] == {{ $optIndex }} ? 'bg-purple-50 border-purple-500 ring-1 ring-purple-500' : ''">
                                                                    <input type="radio" 
                                                                           name="question_{{ $index }}" 
                                                                           value="{{ $optIndex }}" 
                                                                           class="w-5 h-5 text-purple-600 focus:ring-purple-500 border-gray-300"
                                                                           @click="answers[{{ $index }}] = {{ $optIndex }}">
                                                                    <span class="text-slate-600 font-medium group-hover:text-purple-700">{{ $option }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="flex justify-end pt-4">
                                            <button class="bg-purple-600 text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-purple-700 transition shadow-lg shadow-purple-200 hover:-translate-y-1 transform">
                                                Nộp bài kiểm tra
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-8 bg-slate-50 rounded-xl text-center border-2 border-dashed border-slate-200 text-slate-400">
                                        Chưa có câu hỏi nào được tạo.
                                    </div>
                                @endif

                            {{-- 2. LOẠI TÀI LIỆU / VĂN BẢN --}}
                            @else
                                {{-- Nút Download nếu có file --}}
                                @if($activeLesson->file_path && !Str::endsWith($activeLesson->file_path, ['.mp4', '.mov']))
                                    <div class="mb-8 p-5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between group hover:border-purple-200 hover:bg-purple-50/30 transition">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm text-purple-600 border border-slate-100">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-sm group-hover:text-purple-700 transition">Tài liệu đính kèm</h4>
                                                <p class="text-xs text-slate-500">Nhấn để tải xuống máy</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/'.$activeLesson->file_path) }}" target="_blank" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-purple-600 hover:text-white hover:border-purple-600 transition shadow-sm">
                                            Download
                                        </a>
                                    </div>
                                @endif

                                <div class="prose prose-slate prose-lg max-w-none prose-p:leading-loose prose-a:text-blue-600 hover:prose-a:underline prose-img:rounded-2xl">
                                    {!! nl2br(e($activeLesson->content)) !!}
                                </div>
                            @endif

                        @else
                            <div class="py-20 text-center">
                                <p class="text-slate-400 font-medium">Vui lòng chọn một bài học để bắt đầu.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="w-full md:w-96 bg-white border-l border-slate-200 flex flex-col shrink-0 absolute md:relative z-20 h-full transition-transform duration-300 transform"
                 :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full md:translate-x-0 md:w-0 md:hidden'">
                
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between shrink-0">
                    <h3 class="font-black text-slate-800 uppercase text-xs tracking-wider">Nội dung khóa học</h3>
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar" id="playlist">
                    @foreach($course->chapters as $chapter)
                        <div x-data="{ expanded: {{ $activeLesson && $activeLesson->chapter_id == $chapter->id ? 'true' : 'false' }} }" class="border-b border-slate-50">
                            <div @click="expanded = !expanded" class="p-4 bg-white hover:bg-slate-50 cursor-pointer flex justify-between items-start transition group select-none sticky top-0 z-10 border-b border-transparent hover:border-slate-100">
                                <div class="flex-1 pr-2">
                                    <h4 class="font-bold text-sm text-slate-800 group-hover:text-purple-700 transition">{{ $chapter->title }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold mt-1">{{ $chapter->lessons->count() }} bài học</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-300 mt-1 transition-transform duration-200" :class="expanded ? 'rotate-180 text-purple-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>

                            <div x-show="expanded" class="bg-slate-50/30 pb-2" x-collapse>
                                @foreach($chapter->lessons as $lesson)
                                    @php $isActive = isset($activeLesson) && $activeLesson->id == $lesson->id; @endphp
                                    <a href="{{ route('learning.course', ['id' => $course->id, 'lesson_id' => $lesson->id]) }}" 
                                       class="lesson-item block relative group {{ $isActive ? 'bg-purple-50 border-l-4 border-purple-600' : 'hover:bg-white border-l-4 border-transparent hover:border-slate-200' }}"
                                       data-id="{{ $lesson->id }}">
                                        
                                        <div class="p-3 pl-4 flex gap-3">
                                            <div class="mt-0.5 shrink-0">
                                                 @if($isActive)
                                                    <div class="w-5 h-5 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-md animate-pulse">
                                                        <svg class="w-3 h-3 pl-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                    </div>
                                                @else
                                                    @if($lesson->type == 'video')
                                                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 text-slate-300 flex items-center justify-center group-hover:border-purple-400 group-hover:text-purple-400 transition"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
                                                    @elseif($lesson->type == 'quiz')
                                                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 text-slate-300 flex items-center justify-center group-hover:border-emerald-400 group-hover:text-emerald-400 transition"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                                    @else
                                                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 group-hover:border-purple-400 transition"></div>
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="flex-1">
                                                <p class="text-xs font-bold leading-snug {{ $isActive ? 'text-purple-700' : 'text-slate-600 group-hover:text-slate-900' }}">
                                                    {{ $loop->iteration }}. {{ $lesson->title }}
                                                </p>
                                                <div class="flex items-center gap-2 mt-1.5">
                                                    <span class="flex items-center gap-1 text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                                        @if($lesson->type == 'video') VIDEO @elseif($lesson->type == 'quiz') QUIZ @else DOC @endif
                                                        @if($lesson->duration) • {{ $lesson->duration }}m @endif
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
            </div>
        </div>
    </div>

    {{-- Javascript để xử lý Next/Prev Button --}}
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
            
            if(currentIndex !== -1) {
                links[currentIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>
@endsection