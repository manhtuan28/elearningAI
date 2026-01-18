 <style>
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

 <x-app-layout>
     <x-slot name="header">
         <div class="flex items-center gap-4">
             <a href="{{ route('dashboard') }}" class="p-2 rounded-xl bg-white shadow-sm border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                 </svg>
             </a>
             <div>
                 <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] leading-none mb-1">Chi tiết học phần</p>
                 <h2 class="font-black text-xl text-slate-800 leading-tight uppercase">{{ $course->code }}</h2>
             </div>
         </div>
     </x-slot>

     <div class="py-10 bg-[#f8fafc] min-h-screen" x-data="{ tab: 'overview' }">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                 <div class="lg:col-span-8 space-y-6">

                     <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-200/60 relative overflow-hidden">
                         <div class="absolute top-0 right-0 w-40 h-40 bg-blue-50/50 rounded-full -mr-20 -mt-20"></div>
                         <div class="relative z-10">
                             <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-6 leading-tight">{{ $course->title }}</h1>
                             <div class="inline-flex items-center gap-4 p-2 pr-6 bg-slate-50 rounded-2xl border border-slate-100">
                                 <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-xl shadow-lg uppercase">
                                     {{ substr($course->user->name ?? 'G', 0, 1) }}
                                 </div>
                                 <div>
                                     <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Giảng viên hướng dẫn</p>
                                     <p class="text-slate-800 font-bold italic">{{ $course->user->name ?? 'Đang cập nhật' }}</p>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden">
                         <div class="flex border-b border-slate-100 bg-slate-50/50 p-2">
                             <button @click="tab = 'overview'"
                                 :class="tab === 'overview' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                 class="flex-1 py-3 px-4 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                                 Tổng quan & Đề cương
                             </button>
                             <button @click="tab = 'content'"
                                 :class="tab === 'content' ? 'bg-white text-purple-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                 class="flex-1 py-3 px-4 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                                 Nội dung chi tiết
                             </button>
                             <button @click="tab = 'curriculum'"
                                 :class="tab === 'curriculum' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                 class="flex-1 py-3 px-4 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                                 Lộ trình học tập
                             </button>
                         </div>

                         <div class="p-8">
                             <div x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-300">
                                 <h4 class="text-xs font-black text-blue-600 uppercase mb-4 tracking-widest flex items-center gap-2">
                                     <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span> Mô tả đề cương
                                 </h4>
                                 <div class="text-slate-600 leading-relaxed font-medium italic pl-4 border-l-2 border-slate-100">
                                     {!! nl2br(e($course->description)) !!}
                                 </div>
                             </div>

                             <div x-show="tab === 'content'" x-transition:enter="transition ease-out duration-300" style="display: none;">
                                 <h4 class="text-xs font-black text-purple-600 uppercase mb-4 tracking-widest flex items-center gap-2">
                                     <span class="w-1.5 h-4 bg-purple-600 rounded-full"></span> Giáo trình chi tiết
                                 </h4>
                                 <div class="ck-content prose prose-indigo max-w-none text-slate-700 leading-loose">
                                     {!! $course->content !!}
                                 </div>
                             </div>

                             <div x-show="tab === 'curriculum'" x-transition:enter="transition ease-out duration-300" style="display: none;">
                                 <div class="flex justify-between items-center mb-6">
                                     <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2">
                                         <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span> Chương trình đào tạo
                                     </h4>
                                     <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
                                         {{ $course->chapters->count() }} Chương
                                     </span>
                                 </div>
                                 <div class="space-y-4">
                                     @forelse($course->chapters as $chapter)
                                     <div class="group border border-slate-100 rounded-3xl overflow-hidden hover:border-emerald-200 transition-all">
                                         <div class="bg-slate-50/50 px-6 py-4 flex justify-between items-center group-hover:bg-emerald-50/30 transition-colors">
                                             <span class="font-black text-slate-700 text-sm italic">Chương {{ $loop->iteration }}: {{ $chapter->title }}</span>
                                         </div>
                                         <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-2 text-xs font-bold text-slate-500">
                                             @foreach($chapter->lessons as $lesson)
                                             <div class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-50 shadow-sm hover:text-blue-600 transition-all truncate">
                                                 <span>@if($lesson->type == 'video') 🎥 @else 📄 @endif</span>
                                                 <span class="truncate">{{ $lesson->title }}</span>
                                             </div>
                                             @endforeach
                                         </div>
                                     </div>
                                     @empty
                                     <p class="text-center text-slate-400 py-10 italic">Đang cập nhật...</p>
                                     @endforelse
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="lg:col-span-4 lg:sticky lg:top-24">
                     <div class="bg-slate-900 rounded-[3rem] p-3 shadow-2xl shadow-blue-900/20 overflow-hidden">
                         <div class="aspect-[4/3] rounded-[2.5rem] bg-slate-800 overflow-hidden relative border border-white/5">
                             @if($course->image)
                             <img src="{{ asset('storage/' . $course->image) }}" class="w-full h-full object-cover opacity-90 transition-transform duration-700 hover:scale-105">
                             @else
                             <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-900 to-slate-900 text-blue-500/20 font-black text-9xl italic uppercase select-none">
                                 {{ substr($course->title, 0, 1) }}
                             </div>
                             @endif
                             <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                         </div>

                         <div class="p-6">
                             <a href="{{ route('learning.course', $course->id) }}" class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-[2rem] font-black text-center block transition-all shadow-xl shadow-blue-500/20 hover:-translate-y-1 transform uppercase tracking-widest text-sm mb-6">
                                 Bắt đầu học ngay
                             </a>
                             <div class="space-y-4 px-2">
                                 <div class="flex items-center justify-between">
                                     <span class="text-[10px] font-black text-slate-500 uppercase">Đối tượng</span>
                                     <span class="text-[10px] font-black text-white uppercase">{{ $course->classroom->name ?? 'Môn chung' }}</span>
                                 </div>
                                 <div class="flex items-center justify-between border-t border-white/5 pt-4">
                                     <span class="text-[10px] font-black text-slate-500 uppercase italic">Tổng bài giảng</span>
                                     <span class="text-xl font-black text-white italic">{{ $course->chapters->sum(fn($c) => $c->lessons->count()) }}</span>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </div>
     </div>
 </x-app-layout>