<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SiRedis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

    {{-- Background pattern --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-sm relative">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-teal-400 rounded-2xl mb-4 shadow-lg shadow-teal-500/20">
                <span class="text-slate-900 font-bold text-xl">SI</span>
            </div>
            <h1 class="text-white font-bold text-2xl">SiRedis</h1>
            <p class="text-slate-400 text-sm mt-1">Sistem Informasi Rekam Medis</p>
        </div>

        {{-- Card --}}
        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h2 class="text-white font-semibold text-lg mb-6">Masuk ke Sistem</h2>

            @if($errors->any())
            <div class="mb-5 px-4 py-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition"
                           placeholder="email@klinik.com" required autofocus>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Password</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition"
                           placeholder="••••••••" required>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/5 text-teal-500">
                        <span class="text-sm text-slate-400">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-teal-500 hover:bg-teal-400 text-slate-900 font-semibold rounded-xl text-sm transition shadow-lg shadow-teal-500/20 mt-2">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-slate-600 text-xs mt-6">
            &copy; {{ date('Y') }} SiRedis — Klinik Medis
        </p>
    </div>

</body>
</html>