{{-- resources/views/obat/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Obat & Stok')
@section('subtitle', 'Manajemen inventaris obat klinik')

@section('content')

<div class="flex items-center justify-between mb-5 gap-3 flex-wrap">
    <form method="GET" class="flex items-center gap-2">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / kode obat..."
                   class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 w-64 bg-white">
        </div>
        <select name="stok" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
            <option value="">Semua Stok</option>
            <option value="tipis" {{ request('stok') == 'tipis' ? 'selected' : '' }}>Stok Tipis (&lt;10)</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">Filter</button>
        @if(request('search') || request('stok'))
            <a href="{{ route('obat.index') }}" class="text-slate-500 hover:text-slate-700 text-sm px-2">Reset</a>
        @endif
    </form>

    @can('kelola obat')
    <a href="{{ route('obat.create') }}"
       class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Obat
    </a>
    @endcan
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Kode</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama Obat</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Satuan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Stok</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Harga</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($obats as $obat)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-5 py-3.5">
                        <span class="mono text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">{{ $obat->kode_obat }}</span>
                    </td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $obat->nama_obat }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $obat->satuan }}</td>
                    <td class="px-5 py-3.5">
                        @if($obat->stok < 10)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                {{ $obat->stok }}
                            </span>
                        @elseif($obat->stok < 30)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-xs font-semibold">
                                {{ $obat->stok }}
                            </span>
                        @else
                            <span class="text-slate-700 font-semibold mono">{{ $obat->stok }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 mono text-xs">Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1 justify-end">
                            <a href="{{ route('obat.show', $obat) }}"
                               class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @can('kelola obat')
                            <a href="{{ route('obat.edit', $obat) }}"
                               class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('obat.destroy', $obat) }}"
                                  onsubmit="return confirm('Hapus obat {{ $obat->nama_obat }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center text-slate-400">
                        <p class="text-sm">Belum ada data obat</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($obats->hasPages())
    <div class="px-5 py-3 border-t border-slate-100">
        {{ $obats->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>

@endsection