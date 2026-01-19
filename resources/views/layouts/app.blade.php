<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Learning AI') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900 relative selection:bg-blue-500 selection:text-white">

    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-200/40 rounded-full blur-3xl opacity-30 mix-blend-multiply animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-200/40 rounded-full blur-3xl opacity-30 mix-blend-multiply animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-1/3 w-96 h-96 bg-pink-200/40 rounded-full blur-3xl opacity-30 mix-blend-multiply animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        @include('layouts.navigation')

        @if (isset($header))
        <header class="bg-white/80 backdrop-blur-sm shadow-sm sticky top-16 z-40 transition-all border-b border-slate-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        @if (session()->has('success') || session()->has('error'))
        <div x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed top-24 right-5 z-50 max-w-sm w-full bg-white shadow-2xl rounded-xl border-l-4 {{ session()->has('error') ? 'border-red-500' : 'border-green-500' }} p-4 flex items-center gap-3">

            @if(session()->has('success'))
            <div class="text-green-500 bg-green-100 p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800">Thành công!</h4>
                <p class="text-xs text-slate-500">{{ session('success') }}</p>
            </div>
            @else
            <div class="text-red-500 bg-red-100 p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800">Có lỗi xảy ra!</h4>
                <p class="text-xs text-slate-500">{{ session('error') }}</p>
            </div>
            @endif
        </div>
        @endif

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-white/50 backdrop-blur border-t border-slate-200 mt-auto py-6">
            <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-400 font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} E-Learning AI System. All rights reserved.
            </div>
        </footer>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function sendHeartbeat() {
                if (document.visibilityState === 'visible') {
                    axios.post('{{ route("user.heartbeat") }}')
                        .then(res => {
                            // console.log('Heartbeat sent: ', res.data.status);
                        })
                        .catch(err => console.error('Heartbeat failed'));
                }
            }

            sendHeartbeat();

            setInterval(sendHeartbeat, 60000);
        });
    </script>
</body>

</html>