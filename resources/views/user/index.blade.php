{{-- resources/views/user/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Manajemen User')
@section('subtitle', 'Kelola akun pengguna sistem')

@section('content')

<div class="flex items-center justify-between mb-5 gap-3">
    <form method="GET" class="flex items-center gap-2">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / email..."
                   class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 w-64 bg-white">
        </div>
        <select name="role" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
            <option value="">Semua Role</option>
            @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">Filter</button>
        @if(request('search') || request('role'))
            <a href="{{ route('user.index') }}" class="text-slate-500 text-sm px-2">Reset</a>
        @endif
    </form>

    <a href="{{ route('user.create') }}"
       class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah User
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Email</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Role</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Bergabung</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 transition {{ $user->id === auth()->id() ? 'bg-teal-50/30' : '' }}">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-xs shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                @if($user->id === auth()->id())
                                <span class="text-xs text-teal-600 font-medium">(Anda)</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-500 mono text-xs">{{ $user->email }}</td>
                    <td class="px-5 py-3.5">
                        @foreach($user->roles as $role)
                        @php
                            $roleColor = [
                                'superadmin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'admin'      => 'bg-blue-50 text-blue-700 border-blue-200',
                                'dokter'     => 'bg-teal-50 text-teal-700 border-teal-200',
                                'perawat'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            ][$role->name] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border {{ $roleColor }}">
                            {{ ucfirst($role->name) }}
                        </span>
                        @endforeach
                    </td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs mono">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1 justify-end">
                            <a href="{{ route('user.edit', $user) }}"
                               class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if($user->id !== auth()->id() && !$user->hasRole('superadmin'))
                            <form method="POST" action="{{ route('user.destroy', $user) }}"
                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-sm">
                        Tidak ada user ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-5 py-3 border-t border-slate-100">
        {{ $users->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>

@endsection