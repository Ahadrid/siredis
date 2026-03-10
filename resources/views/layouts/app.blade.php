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
    <aside class="w-60 bg-slate-900 flex flex-col fixed top-0 left-0 h-full z-30 shadow-xl">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-white/10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-teal-400 rounded-lg flex items-center justify-center text-slate-900 font-bold text-sm">SI</div>
                <div>
                    <p class="text-white font-bold text-sm leading-none">SiRedis</p>
                    <p class="text-slate-400 text-xs mt-0.5">Rekam Medis Digital</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

            <p class="text-white text-xs font-semibold uppercase tracking-widest px-3 mb-2">Utama</p>

            <a href="{{ route('dashboard') }}"
               class="text-slate-400 sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            @can('lihat pasien')
            <a href="{{ route('pasien.index') }}"
               class="text-slate-400 sidebar-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pasien
            </a>
            @endcan

            @can('lihat kunjungan')
            <a href="{{ route('kunjungan.index') }}"
               class="text-slate-400 sidebar-link {{ request()->routeIs('kunjungan.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Kunjungan
            </a>
            @endcan

            @can('lihat rekam medis')
            <a href="{{ route('rekam-medis.index') }}"
               class="text-slate-400 sidebar-link {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Rekam Medis
            </a>
            @endcan

            @can('lihat obat')
            <p class="text-white text-xs font-semibold uppercase tracking-widest px-3 mb-2 mt-4">Farmasi</p>
            <a href="{{ route('obat.index') }}"
               class="text-slate-400 sidebar-link {{ request()->routeIs('obat.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Obat & Stok
            </a>
            @endcan

            @role('superadmin|admin')
            <p class="text-white text-xs font-semibold uppercase tracking-widest px-3 mb-2 mt-4">Sistem</p>
            <a href="{{ route('user.index') }}"
               class="text-slate-400 sidebar-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                Manajemen User
            </a>
            @endrole
        </nav>

        {{-- User Info --}}
        <div class="px-3 py-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-white/5">
                <div class="w-8 h-8 rounded-full bg-teal-400 flex items-center justify-center text-slate-900 font-bold text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-xs capitalize">{{ auth()->user()->getRoleNames()->first() }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button title="Logout" class="text-slate-400 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

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
            @if(session('success'))
            <div 
                x-data="{ show:true }"
                x-init="setTimeout(() => show=false, 3500)"
                x-show="show"
                x-transition:enter="transform ease-out duration-300"
                x-transition:enter-start="translate-x-10 opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transform ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"

                class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm shadow-lg"
            >
                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>

                <span class="flex-1">
                    {{ session('success') }}
                </span>

                <button @click="show=false" class="text-slate-400 hover:text-slate-700">
                    ✕
                </button>
            </div>
            @endif


            {{-- ERROR --}}
            @if(session('error'))
            <div 
                x-data="{ show:true }"
                x-init="setTimeout(() => show=false, 4000)"
                x-show="show"
                x-transition

                class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm shadow-lg"
            >
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>

                <span class="flex-1">
                    {{ session('error') }}
                </span>

                <button @click="show=false" class="text-slate-400 hover:text-slate-700">
                    ✕
                </button>
            </div>
            @endif

        </div>

        {{-- Page Content --}}
        <main class="flex-1 px-6 py-4">
            @yield('content')
        </main>

        <footer class="px-6 py-3 border-t border-slate-200 text-xs text-slate-400 text-center">
            SiRedis &copy; {{ date('Y') }} — Sistem Informasi Rekam Medis Digital - by Ahadrid
        </footer>
    </div>
</div>

@stack('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>