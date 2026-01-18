<title>Biên soạn: {{ $course->title }}</title>
@extends('layouts.admin')

<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    .ck-editor__editable_inline { min-height: 450px !important; padding: 2rem !important; }
    .ck-content { font-family: 'Inter', sans-serif; line-height: 1.8; color: #334155; }
    .ck-content h1, .ck-content h2, .ck-content h3, .ck-content h4 { color: #1e293b !important; font-weight: 800 !important; margin-top: 2rem !important; margin-bottom: 1rem !important; display: block !important; }
    .ck-content h1 { font-size: 2.25rem !important; border-bottom: 3px solid #f1f5f9; padding-bottom: 0.5rem; }
    .ck-content h2 { font-size: 1.875rem !important; border-left: 4px solid #3b82f6; padding-left: 1rem; }
    .ck-content ul { list-style-type: disc !important; padding-left: 2.5rem !important; }
    .ck-content ol { list-style-type: decimal !important; padding-left: 2.5rem !important; }
    .timeline-line::before { content: ''; position: absolute; left: 19px; top: 0; bottom: 0; width: 2px; background: #f1f5f9; z-index: 0; }
</style>

@section('content')
<div x-data="manageApp()" x-init="initEditors()">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-tight uppercase italic">{{ $course->title }}</h1>
            <div class="mt-2 flex items-center gap-3 font-bold text-sm text-slate-500 italic">
                <span class="px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-[10px] font-black uppercase border border-purple-100 italic">{{ $course->code }}</span>
                <span>•</span>
                <p class="uppercase text-[10px] tracking-widest">Hệ thống quản lý bài giảng</p>
            </div>
        </div>
        <a href="{{ route('instructor.dashboard') }}" class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-500 hover:text-purple-600 transition shadow-sm">
            &larr; Quay lại Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="mb-8 p-4 bg-emerald-50 text-emerald-700 rounded-2xl font-bold border border-emerald-100 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8 space-y-10 relative timeline-line">
            @foreach($course->chapters as $chapter)
            <div class="relative z-10" x-data="{ expanded: true }">
                <div class="absolute left-0 w-10 h-10 rounded-full bg-white border-4 border-purple-500 shadow-md flex items-center justify-center font-black text-purple-600 z-20">
                    {{ $loop->iteration }}
                </div>

                <div class="ml-16 bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-500">
                    <div class="p-6 flex items-center justify-between bg-slate-50/50 border-b border-slate-100">
                        <div class="cursor-pointer flex-1" @click="expanded = !expanded">
                            <h3 class="text-xl font-black text-slate-800 uppercase italic">{{ $chapter->title }}</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <button @click="openAddLesson({{ $chapter->id }}, '{{ addslashes($chapter->title) }}')" class="px-4 py-2 bg-purple-600 text-white text-[10px] font-black uppercase rounded-xl hover:bg-purple-700 shadow-lg shadow-purple-100">+ Bài học</button>
                            <form action="{{ route('instructor.chapters.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Xóa toàn bộ chương này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition bg-transparent border-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div x-show="expanded" x-collapse class="p-4 space-y-2 bg-white">
                        @forelse($chapter->lessons as $lesson)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-purple-200 hover:bg-white transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-lg">
                                    @if($lesson->type == 'video') 🎥 @elseif($lesson->type == 'quiz') ❓ @elseif($lesson->type == 'homework') 📝 @else 📄 @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 text-sm italic">{{ $lesson->title }}</p>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $lesson->type }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="openEditModal({{ json_encode($lesson) }}, '{{ route('instructor.lessons.update', $lesson->id) }}')"
                                    class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition border border-slate-100 bg-white shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('instructor.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Xóa bài học?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition bg-transparent border-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <p class="text-center py-4 text-slate-400 text-[10px] font-black italic">Chương này trống</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach

            <div x-data="{ open: false }" class="ml-16">
                <button @click="open = !open" x-show="!open"
                    class="w-full py-6 border-2 border-dashed border-slate-300 rounded-[2.5rem] text-slate-400 font-black uppercase text-[10px] hover:border-purple-500 hover:text-purple-600 transition-all flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    THÊM CHƯƠNG HỌC MỚI
                </button>
                <div x-show="open" class="bg-white p-8 rounded-[2.5rem] shadow-2xl border border-purple-100 relative" x-transition>
                    <form action="{{ route('instructor.chapters.store', $course->id) }}" method="POST">
                        @csrf
                        <div class="flex gap-4">
                            <input type="text" name="title" required placeholder="Tên chương mới..." class="flex-1 rounded-2xl border-slate-200 font-bold px-6 py-4 focus:ring-purple-500 shadow-inner">
                            <button type="submit" class="bg-purple-600 text-white px-8 py-4 rounded-2xl font-black uppercase text-xs">Lưu chương</button>
                            <button type="button" @click="open = false" class="text-slate-400 font-bold">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 lg:sticky lg:top-24 h-fit">
            <div class="bg-slate-900 rounded-[3rem] p-8 shadow-2xl text-white">
                <h3 class="font-black uppercase tracking-widest text-[10px] mb-8 text-purple-400 border-b border-white/10 pb-4 italic">Trình trạng giáo trình</h3>
                <div class="space-y-4 text-sm font-bold">
                    <div class="flex justify-between"><span>🎥 Video</span><span class="text-white italic">{{ $course->chapters->sum(fn($c)=>$c->lessons->where('type','video')->count()) }}</span></div>
                    <div class="flex justify-between"><span>📄 Tài liệu</span><span class="text-white italic">{{ $course->chapters->sum(fn($c)=>$c->lessons->where('type','document')->count()) }}</span></div>
                    <div class="flex justify-between border-t border-white/5 pt-4 mt-4 text-purple-400 text-xl font-black italic">
                        <span>TỔNG BÀI</span>
                        <span>{{ $course->chapters->sum(fn($c)=>$c->lessons->count()) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" style="display: none;" x-transition>
        <div class="bg-white p-10 rounded-[3rem] w-full max-w-3xl shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6">
                <h3 class="font-black text-3xl text-slate-800 uppercase italic" x-text="isEdit ? 'Sửa bài học' : 'Thêm bài học'"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-red-500 transition border-none bg-transparent">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form @submit.prevent="submitLesson">
                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest">Tiêu đề</label>
                            <input type="text" x-model="lesson.title" required class="w-full rounded-2xl border-slate-200 font-bold px-6 py-4 bg-slate-50 focus:bg-white transition-all shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest">Loại bài học</label>
                            <select x-model="lesson.type" class="w-full rounded-2xl border-slate-200 font-bold px-6 py-4 bg-slate-50 shadow-inner">
                                <option value="video">🎥 Video</option>
                                <option value="document">📄 Tài liệu</option>
                                <option value="homework">📝 Bài tập</option>
                                <option value="quiz">❓ Quiz</option>
                            </select>
                        </div>
                    </div>

                    <div x-show="['video', 'document'].includes(lesson.type)" class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 space-y-4 shadow-inner">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1 tracking-widest italic" x-text="lesson.type === 'video' ? 'Upload Video' : 'Upload Tài liệu'"></label>
                        <input type="file" @change="handleFile($event)" class="text-[10px] font-black text-slate-400 w-full block">
                        
                        <template x-if="isEdit && lesson.file_path">
                            <p class="text-[10px] text-emerald-600 font-bold italic">✓ Đã có file: <span x-text="lesson.file_path.split('/').pop()"></span></p>
                        </template>

                        <div x-show="lesson.type === 'video'" class="space-y-4 pt-2">
                            <input type="text" x-model="lesson.video_url" placeholder="Youtube link..." class="w-full rounded-xl border-slate-200 text-sm font-bold shadow-sm px-4 py-3">
                            <input type="number" x-model="lesson.duration" placeholder="Thời lượng (Phút)" class="w-40 rounded-xl border-slate-200 text-sm font-bold shadow-sm px-4 py-3">
                        </div>
                    </div>

                    <div x-show="['document', 'homework'].includes(lesson.type)" class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic">Nội dung chi tiết</label>
                        <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-xl"><textarea id="editor"></textarea></div>
                    </div>

                    <div x-show="lesson.type === 'quiz'" class="p-8 bg-emerald-50 rounded-[2.5rem] border border-emerald-100 space-y-6">
                        <div class="flex justify-between items-center"><label class="text-xs font-black text-emerald-600 uppercase italic">Hệ thống câu hỏi</label><button type="button" @click="addQuestion()" class="text-[10px] bg-white text-emerald-600 px-4 py-2 rounded-xl font-black uppercase shadow-sm border border-emerald-200">+ Thêm</button></div>
                        <template x-for="(q, idx) in questions" :key="idx">
                            <div class="bg-white p-6 rounded-3xl border border-emerald-100 mb-3 shadow-sm relative">
                                <input type="text" x-model="q.question" placeholder="Câu hỏi..." class="w-full border-none border-b border-emerald-100 p-0 font-bold mb-5 focus:ring-0 text-slate-700">
                                <div class="grid grid-cols-2 gap-4 text-xs font-bold">
                                    <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                        <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-xl">
                                            <input type="radio" :checked="q.correct == oIdx" @change="q.correct = oIdx" class="text-emerald-500">
                                            <input type="text" x-model="q.options[oIdx]" class="flex-1 bg-transparent border-none text-[10px]" placeholder="Đáp án...">
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="removeQuestion(idx)" class="text-[9px] text-red-400 mt-4 font-black uppercase italic" x-show="questions.length > 1">Xóa</button>
                            </div>
                        </template>
                    </div>

                    <div x-show="uploading" class="p-6 bg-purple-50 rounded-2xl">
                        <div class="flex justify-between mb-2"><span class="text-[10px] font-black text-purple-600 uppercase italic">Đang đồng bộ...</span><span class="text-xs font-black text-purple-600" x-text="progress + '%'"></span></div>
                        <div class="w-full bg-white h-3 rounded-full overflow-hidden shadow-inner border border-purple-100">
                            <div class="bg-purple-600 h-full transition-all duration-300 shadow-lg" :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>

                    <button type="submit" :disabled="uploading" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black uppercase text-sm hover:bg-black transition shadow-2xl shadow-slate-300 italic tracking-[0.2em]">
                        <span x-text="uploading ? 'VUI LÒNG ĐỢI...' : 'XÁC NHẬN CẬP NHẬT'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function manageApp() {
        return {
            modalOpen: false, isEdit: false, uploading: false, progress: 0,
            activeChapterId: null, actionUrl: '',
            lesson: { id: null, title: '', type: 'video', duration: 0, video_url: '', content: '', file_path: '' },
            file: null, questions: [],

            initEditors() {
                ClassicEditor.create(document.querySelector('#editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
                }).then(ed => {
                    window.lessonEditor = ed;
                    ed.editing.view.change(writer => {
                        writer.addClass('ck-content', ed.editing.view.document.getRoot());
                    });
                });
            },

            openAddLesson(chapterId, chapterName) {
                this.isEdit = false;
                this.activeChapterId = chapterId;
                this.lesson = { id: null, title: '', type: 'video', duration: 0, video_url: '', content: '', file_path: '' };
                this.questions = [{ question: '', options: ['', '', '', ''], correct: 0 }];
                if (window.lessonEditor) window.lessonEditor.setData('');
                this.actionUrl = `/instructor/chapters/${chapterId}/lessons`;
                this.modalOpen = true;
                this.progress = 0;
                this.file = null;
            },

            openEditModal(data, url) {
                this.isEdit = true;
                this.actionUrl = url;
                const cleanData = JSON.parse(JSON.stringify(data));
                this.lesson = {
                    id: cleanData.id,
                    title: cleanData.title,
                    type: cleanData.type,
                    duration: cleanData.duration || 0,
                    video_url: cleanData.video_url || '',
                    content: cleanData.content || '',
                    file_path: cleanData.file_path || ''
                };

                if (window.lessonEditor) window.lessonEditor.setData(this.lesson.content);

                if (cleanData.type === 'quiz' && cleanData.content) {
                    try { this.questions = JSON.parse(cleanData.content); } catch (e) { this.questions = [{ question: '', options: ['', '', '', ''], correct: 0 }]; }
                } else {
                    this.questions = [{ question: '', options: ['', '', '', ''], correct: 0 }];
                }
                this.modalOpen = true;
                this.progress = 0;
                this.file = null;
            },

            addQuestion() { this.questions.push({ question: '', options: ['', '', '', ''], correct: 0 }); },
            removeQuestion(idx) { this.questions.splice(idx, 1); },
            handleFile(e) { this.file = e.target.files[0]; },

            async submitLesson() {
                this.uploading = true;
                this.progress = 0;
                const fd = new FormData();
                fd.append('title', this.lesson.title);
                fd.append('type', this.lesson.type);
                fd.append('duration', this.lesson.duration);
                fd.append('video_url', this.lesson.video_url || '');

                if (this.isEdit) fd.append('_method', 'PUT');

                // Dùng chung tên file_upload cho đồng bộ với Controller
                if (this.file) {
                    fd.append('file_upload', this.file); 
                }

                if (['document', 'homework'].includes(this.lesson.type) && window.lessonEditor) {
                    const fieldName = this.lesson.type === 'document' ? 'content_doc' : 'content_homework';
                    fd.append(fieldName, window.lessonEditor.getData());
                }

                if (this.lesson.type === 'quiz') {
                    this.questions.forEach((q, i) => {
                        fd.append(`quiz[${i}][question]`, q.question);
                        fd.append(`quiz[${i}][correct]`, q.correct);
                        q.options.forEach(opt => fd.append(`quiz[${i}][options][]`, opt));
                    });
                }

                try {
                    await axios.post(this.actionUrl, fd, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                        onUploadProgress: (p) => this.progress = Math.round((p.loaded * 100) / p.total)
                    });
                    location.reload();
                } catch (err) {
                    alert('Lỗi: ' + (err.response?.data?.message || err.message));
                    this.uploading = false;
                }
            }
        }
    }
</script>
@endsection