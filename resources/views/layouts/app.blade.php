<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiRedis — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .sidebar-link { @apply flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:bg-white/10 hover:text-white transition-all duration-150 text-sm font-medium; }
        .sidebar-link.active { @apply bg-white/15 text-white; }
        .badge-status-menunggu    { @apply bg-amber-100 text-amber-700 border border-amber-200; }
        .badge-status-dalam_proses{ @apply bg-blue-100 text-blue-700 border border-blue-200; }
        .badge-status-selesai     { @apply bg-emerald-100 text-emerald-700 border border-emerald-200; }
        .badge-status-batal       { @apply bg-red-100 text-red-700 border border-red-200; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="flex min-h-screen">

        {{-- ══════════════ SIDEBAR ══════════════ --}}
        @include('layouts.sidebar')

        {{-- ══════════════ MAIN CONTENT ══════════════ --}}
        <div class="flex-1 ml-60 flex flex-col min-h-screen">

            {{-- Topbar --}}
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-20">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">@yield('title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-400 mt-0.5">@yield('subtitle', '')</p>
                </div>
                <div class="text-xs text-slate-400 mono">
                    {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                </div>
            </header>

            {{-- Alerts --}}
            <div class="fixed top-5 right-5 z-50 space-y-3 w-80">
                {{-- SUCCESS --}}
                @include('components.success')

                {{-- ERROR --}}
                @include('components.error')

            </div>

            {{-- Page Content --}}
            <main class="flex-1 px-6 py-4">
                @yield('content')
            </main>

            @include('layouts.footer')
        </div>
    </div>

@stack('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>