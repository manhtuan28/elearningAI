<title>Biên soạn: {{ $course->title }}</title>
@extends('layouts.admin')
<script src="//unpkg.com/alpinejs" defer></script>

@section('content')
    <div x-data="{ 
        editModalOpen: false, 
        actionUrl: '',
        lesson: { title: '', type: 'video', duration: 0, video_url: '', content: '' },
        questions: [],

        openEdit(data, url) {
            this.actionUrl = url;
            this.lesson = Object.assign({}, data); 
            
            // Xử lý Quiz JSON
            if (this.lesson.type === 'quiz' && this.lesson.content) {
                try { 
                    this.questions = JSON.parse(this.lesson.content); 
                } catch(e) { 
                    this.questions = [{ question: '', options: ['', '', '', ''], correct: 0 }]; 
                }
            } else {
                this.questions = [{ question: '', options: ['', '', '', ''], correct: 0 }];
            }
            this.editModalOpen = true;
        }
    }">

        <div class="mb-8 border-b border-slate-100 pb-6">
            <a href="{{ route('instructor.dashboard') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-purple-600 font-bold text-sm mb-4 transition group">
                <span class="p-1 rounded-full bg-slate-50 group-hover:bg-purple-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </span>
                Quay lại Dashboard
            </a>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">{{ $course->title }}</h1>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="px-3 py-1 rounded-md bg-purple-50 text-purple-700 text-xs font-black uppercase tracking-wider border border-purple-100">{{ $course->code }}</span>
                        <span class="text-slate-400 text-sm font-medium">|</span>
                        <p class="text-slate-500 font-medium text-sm">Trình biên soạn nội dung</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold border border-emerald-100 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                @foreach($course->chapters as $chapter)
                    <div x-data="{ expanded: {{ $loop->last ? 'true' : 'false' }}, editing: false }" 
                         class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-md">
                        
                        <div class="bg-slate-50/50 p-4 border-b border-slate-100 flex justify-between items-center group">
                            <div class="flex-1 flex items-center gap-3 cursor-pointer select-none" x-show="!editing" @click="expanded = !expanded">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 flex items-center justify-center transition-transform duration-300" 
                                     :class="expanded ? 'rotate-90 text-purple-600 border-purple-200 bg-purple-50' : ''">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-lg group-hover:text-purple-700 transition">{{ $chapter->title }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $chapter->lessons->count() }} bài học</p>
                                </div>
                            </div>

                            <div x-show="editing" class="flex-1 flex items-center gap-2 pr-4" style="display: none;">
                                <form action="{{ route('instructor.chapters.update', $chapter->id) }}" method="POST" class="w-full flex gap-2">
                                    @csrf @method('PUT')
                                    <input type="text" name="title" value="{{ $chapter->title }}" class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-sm font-bold focus:ring-purple-500" autofocus>
                                    <button class="bg-green-600 text-white px-3 py-2 rounded-lg text-xs font-bold">Lưu</button>
                                    <button type="button" @click="editing = false" class="bg-slate-200 text-slate-600 px-3 py-2 rounded-lg text-xs font-bold">Hủy</button>
                                </form>
                            </div>

                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200" x-show="!editing">
                                <button @click="editing = true" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                <form action="{{ route('instructor.chapters.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Xóa chương này?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </div>

                        <div x-show="expanded" x-collapse class="bg-white">
                            <div class="divide-y divide-slate-50">
                                @foreach($chapter->lessons as $lesson)
                                    <div class="group flex items-center justify-between p-4 pl-6 hover:bg-purple-50/40 transition duration-200">
                                        <div class="flex items-start gap-4">
                                            <div class="mt-0.5 shrink-0">
                                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-black text-[10px] uppercase">
                                                    {{ substr($lesson->type, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-700 text-sm group-hover:text-purple-700 transition">{{ $lesson->title }}</span>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $lesson->type }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-200">
                                            <button type="button" @click="openEdit({{ Js::from($lesson) }}, '{{ route('instructor.lessons.update', $lesson->id) }}')"
                                                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition border border-blue-100 bg-white shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('instructor.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Xóa bài?')">
                                                @csrf @method('DELETE')
                                                <button class="p-1.5 text-red-400 hover:text-red-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div x-data="{ formOpen: false, type: 'video', questions: [{ question: '', options: ['', '', '', ''], correct: 0 }] }" class="p-4 bg-slate-50/50 border-t border-slate-50">
                                <button x-show="!formOpen" @click="formOpen = true" class="w-full py-3 border-2 border-dashed border-slate-300 rounded-xl text-xs font-black text-slate-400 hover:text-purple-600 hover:border-purple-400 transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    THÊM NỘI DUNG VÀO CHƯƠNG
                                </button>
                                
                                <div x-show="formOpen" class="bg-white p-6 rounded-xl border border-purple-100 shadow-xl relative" x-transition>
                                    <button @click="formOpen = false" class="absolute top-4 right-4 text-slate-300 hover:text-red-500 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>

                                    <form action="{{ route('instructor.lessons.store', $chapter->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-3 gap-4">
                                                <div class="col-span-2">
                                                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Tiêu đề bài học</label>
                                                    <input type="text" name="title" required class="w-full rounded-lg border-slate-200 text-sm font-bold">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Loại</label>
                                                    <select name="type" class="w-full rounded-lg border-slate-200 text-xs font-bold" x-model="type">
                                                        <option value="video">🎥 Video</option>
                                                        <option value="document">📄 Tài liệu</option>
                                                        <option value="homework">📝 Bài tập</option>
                                                        <option value="quiz">❓ Quiz</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div x-show="type === 'video'" class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-3">
                                                <div><label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Upload Video</label><input type="file" name="video_file" class="text-xs"></div>
                                                <div class="text-center text-[10px] text-slate-300 font-bold uppercase tracking-widest">— Hoặc link Youtube —</div>
                                                <div><input type="text" name="video_url" placeholder="Link video..." class="w-full rounded-lg border-slate-200 text-xs px-3 py-2"></div>
                                                <div><input type="number" name="duration" placeholder="Thời lượng (Phút)" class="w-32 rounded-lg border-slate-200 text-xs px-3 py-2"></div>
                                            </div>

                                            <div x-show="type === 'document'" class="p-4 bg-blue-50 rounded-lg border border-blue-100 space-y-3">
                                                <textarea name="content_doc" rows="4" class="w-full rounded-lg border-blue-200 text-sm" placeholder="Nội dung văn bản..."></textarea>
                                                <div><label class="block text-[10px] font-black uppercase text-blue-400 mb-1">Đính kèm file</label><input type="file" name="doc_file" class="text-xs"></div>
                                            </div>

                                            <div x-show="type === 'homework'" class="p-4 bg-orange-50 rounded-lg border border-orange-100 space-y-3">
                                                <textarea name="content_homework" rows="4" class="w-full rounded-lg border-orange-200 text-sm" placeholder="Yêu cầu bài tập..."></textarea>
                                            </div>

                                            <div x-show="type === 'quiz'" class="p-4 bg-emerald-50 rounded-lg border border-emerald-100 space-y-4">
                                                <div class="flex justify-between"><label class="text-[10px] font-black uppercase text-emerald-600">Câu hỏi trắc nghiệm</label><button type="button" @click="questions.push({ question: '', options: ['', '', '', ''], correct: 0 })" class="text-[10px] bg-white border border-emerald-200 text-emerald-600 px-2 py-1 rounded font-bold uppercase">+ Thêm câu hỏi</button></div>
                                                <template x-for="(q, index) in questions" :key="index">
                                                    <div class="bg-white p-3 rounded-lg border border-emerald-200 relative shadow-sm">
                                                        <div class="absolute top-2 right-2 text-[10px] font-black text-emerald-100" x-text="'#' + (index + 1)"></div>
                                                        <input type="text" :name="'quiz[' + index + '][question]'" x-model="q.question" placeholder="Nhập câu hỏi..." class="w-full border-none border-b border-emerald-50 p-0 text-sm font-bold mb-3 focus:ring-0">
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <template x-for="(opt, optIndex) in q.options" :key="optIndex">
                                                                <div class="flex items-center gap-2">
                                                                    <input type="radio" :name="'quiz[' + index + '][correct]'" :value="optIndex" class="text-emerald-500">
                                                                    <input type="text" :name="'quiz[' + index + '][options][]'" x-model="q.options[optIndex]" placeholder="Đáp án..." class="flex-1 rounded-md border-slate-100 text-xs py-1 px-2 bg-slate-50">
                                                                </div>
                                                            </template>
                                                        </div>
                                                        <button type="button" @click="questions = questions.filter((_, i) => i !== index)" class="text-[9px] text-red-400 mt-2 hover:underline uppercase font-bold" x-show="questions.length > 1">Xóa</button>
                                                    </div>
                                                </template>
                                            </div>

                                            <button class="w-full bg-slate-900 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition shadow-lg shadow-slate-200">Lưu dữ liệu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div x-data="{ open: false }" class="mt-8">
                    <button @click="open = !open" x-show="!open" class="w-full py-5 border-2 border-dashed border-slate-300 rounded-2xl text-slate-400 font-black uppercase tracking-widest hover:border-purple-500 hover:text-purple-600 hover:bg-purple-50 transition flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Thêm chương học mới
                    </button>
                    <div x-show="open" class="bg-white p-8 rounded-2xl shadow-2xl border border-purple-100 relative" x-transition>
                        <button @click="open = false" class="absolute top-4 right-4 text-slate-300 hover:text-red-500 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        <form action="{{ route('instructor.chapters.store', $course->id) }}" method="POST">
                            @csrf
                            <label class="block text-xs font-black text-slate-400 uppercase mb-2">Tên chương mới</label>
                            <div class="flex gap-3">
                                <input type="text" name="title" placeholder="Ví dụ: Giới thiệu căn bản..." required class="flex-1 rounded-xl border-slate-200 font-bold px-4 py-3 focus:ring-purple-500">
                                <button class="bg-purple-600 text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest hover:bg-purple-700 shadow-lg shadow-purple-100 transition">Lưu chương</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-6">
                    <h3 class="font-black text-slate-800 uppercase mb-4 text-xs tracking-widest border-b border-slate-100 pb-3">Tổng quan khóa học</h3>
                    <ul class="space-y-3">
                        <li class="flex justify-between text-sm p-3 bg-slate-50 rounded-xl"><span class="text-slate-500 font-bold">🎥 Video</span><span class="font-black text-slate-800">{{ $course->chapters->sum(function($c) { return $c->lessons->where('type', 'video')->count(); }) }}</span></li>
                        <li class="flex justify-between text-sm p-3 bg-slate-50 rounded-xl"><span class="text-slate-500 font-bold">📄 Tài liệu</span><span class="font-black text-slate-800">{{ $course->chapters->sum(function($c) { return $c->lessons->where('type', 'document')->count(); }) }}</span></li>
                        <li class="flex justify-between text-sm p-3 bg-slate-50 rounded-xl"><span class="text-slate-500 font-bold">📝 Bài tập</span><span class="font-black text-slate-800">{{ $course->chapters->sum(function($c) { return $c->lessons->where('type', 'homework')->count(); }) }}</span></li>
                        <li class="flex justify-between text-sm p-3 bg-slate-50 rounded-xl"><span class="text-slate-500 font-bold">❓ Quiz</span><span class="font-black text-slate-800">{{ $course->chapters->sum(function($c) { return $c->lessons->where('type', 'quiz')->count(); }) }}</span></li>
                    </ul>
                </div>
            </div>

            <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition>
                <div class="bg-white p-8 rounded-3xl w-full max-w-xl shadow-2xl h-[85vh] overflow-y-auto" @click.outside="editModalOpen = false">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="font-black text-2xl text-slate-800 uppercase tracking-tight">Cập nhật nội dung</h3>
                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-red-500 transition"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>

                    <form :action="actionUrl" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase mb-2">Tiêu đề bài học</label>
                                <input type="text" name="title" x-model="lesson.title" class="w-full rounded-2xl border-slate-200 font-bold px-5 py-4 focus:ring-purple-500 focus:bg-white bg-slate-50 transition">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Loại bài</label>
                                    <select name="type" x-model="lesson.type" class="w-full rounded-2xl border-slate-200 font-black text-xs bg-slate-100 px-5 py-4 uppercase">
                                        <option value="video">Video</option>
                                        <option value="document">Tài liệu</option>
                                        <option value="homework">Bài tập</option>
                                        <option value="quiz">Trắc nghiệm</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Thời lượng (Phút)</label>
                                    <input type="number" name="duration" x-model="lesson.duration" class="w-full rounded-2xl border-slate-200 font-bold px-5 py-4 bg-slate-50">
                                </div>
                            </div>

                            <div x-show="lesson.type === 'video'" class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                                <div><label class="block text-[10px] font-black uppercase text-slate-400 mb-2">File Video mới (Tùy chọn)</label><input type="file" name="file" class="text-xs"></div>
                                <div class="text-center text-[10px] font-black text-slate-300 uppercase tracking-widest">— HOẶC —</div>
                                <div><label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Link Youtube</label><input type="text" name="video_url" x-model="lesson.video_url" class="w-full rounded-xl border-slate-200 text-sm px-4 py-3 bg-white"></div>
                            </div>

                            <div x-show="lesson.type === 'document'" class="p-5 bg-blue-50 rounded-2xl border border-blue-100 space-y-4">
                                <label class="block text-[10px] font-black uppercase text-blue-500">Nội dung văn bản</label>
                                <textarea name="content_doc" rows="6" class="w-full rounded-xl border-blue-200 text-sm p-4 bg-white" x-model="lesson.content"></textarea>
                                <div><label class="block text-[10px] font-black uppercase text-blue-400 mb-1">Thay file (PDF/Word)</label><input type="file" name="file" class="text-xs"></div>
                            </div>

                            <div x-show="lesson.type === 'homework'" class="p-5 bg-orange-50 rounded-2xl border border-orange-100 space-y-2">
                                <label class="block text-[10px] font-black uppercase text-orange-500">Yêu cầu bài tập</label>
                                <textarea name="content_homework" rows="6" class="w-full rounded-xl border-orange-200 text-sm p-4 bg-white" x-model="lesson.content"></textarea>
                            </div>

                            <div x-show="lesson.type === 'quiz'" class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100 space-y-5">
                                <div class="flex justify-between items-center">
                                    <label class="text-[10px] font-black uppercase text-emerald-600">Câu hỏi trắc nghiệm</label>
                                    <button type="button" @click="questions.push({ question: '', options: ['', '', '', ''], correct: 0 })" class="text-[10px] bg-white border border-emerald-200 text-emerald-600 px-3 py-2 rounded-xl font-black uppercase">+ Thêm câu hỏi</button>
                                </div>
                                <template x-for="(q, index) in questions" :key="index">
                                    <div class="bg-white p-5 rounded-2xl border border-emerald-100 relative shadow-md">
                                        <div class="absolute top-3 right-4 text-xs font-black text-emerald-100" x-text="'#' + (index + 1)"></div>
                                        <input type="text" :name="'quiz[' + index + '][question]'" x-model="q.question" class="w-full border-none border-b border-emerald-50 p-0 text-sm font-bold mb-4 focus:ring-0" placeholder="Câu hỏi là gì?">
                                        <div class="grid grid-cols-2 gap-3">
                                            <template x-for="(opt, optIndex) in q.options" :key="optIndex">
                                                <div class="flex items-center gap-2">
                                                    <input type="radio" :name="'quiz[' + index + '][correct]'" :value="optIndex" :checked="q.correct == optIndex" @change="q.correct = optIndex" class="text-emerald-500 focus:ring-emerald-400">
                                                    <input type="text" :name="'quiz[' + index + '][options][]'" x-model="q.options[optIndex]" class="flex-1 rounded-lg border-slate-100 text-xs py-2 px-3 bg-slate-50 focus:bg-white transition" placeholder="Đáp án...">
                                                </div>
                                            </template>
                                        </div>
                                        <button type="button" @click="questions = questions.filter((_, i) => i !== index)" class="text-[9px] text-red-400 mt-4 font-black uppercase hover:underline" x-show="questions.length > 1">Xóa câu hỏi này</button>
                                    </div>
                                </template>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="w-full bg-slate-900 text-white px-8 py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-black transition shadow-2xl shadow-slate-200">
                                    CẬP NHẬT TOÀN BỘ THAY ĐỔI
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection