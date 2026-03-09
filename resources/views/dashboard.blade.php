@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Total Pasien</p>
            <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $total_pasien }}</p>
            <p class="text-xs text-slate-400 mt-1">terdaftar</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Kunjungan Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $kunjungan_hari_ini }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ now()->format('d M Y') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Menunggu</p>
            <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $kunjungan_menunggu }}</p>
            <p class="text-xs text-slate-400 mt-1">antrian aktif</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Stok Obat Tipis</p>
            <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $obat_stok_tipis }}</p>
            <p class="text-xs text-red-400 mt-1">perlu restock</p>
        </div>
    </div>

</div>

{{-- Tabel Kunjungan Terbaru --}}
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-800 text-sm">Kunjungan Terbaru</h3>
            <p class="text-xs text-slate-400 mt-0.5">5 kunjungan terakhir</p>
        </div>
        @can('lihat kunjungan')
        <a href="{{ route('kunjungan.index') }}"
           class="text-xs text-teal-600 hover:text-teal-700 font-medium">Lihat semua →</a>
        @endcan
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">No. Kunjungan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Pasien</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Dokter</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($kunjungan_terbaru as $k)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-5 py-3.5 mono text-xs text-slate-600">{{ $k->no_kunjungan }}</td>
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-slate-800">{{ $k->pasien->nama }}</div>
                        <div class="text-xs text-slate-400 mono">{{ $k->pasien->no_rm }}</div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $k->dokter->name }}</td>
                    <td class="px-5 py-3.5 text-slate-500 text-xs mono">{{ $k->tanggal_kunjungan->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3.5">
                        @php
                            $statusMap = [
                                'menunggu'     => 'bg-amber-50 text-amber-700 border-amber-200',
                                'dalam_proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'selesai'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'batal'        => 'bg-red-50 text-red-600 border-red-200',
                            ];
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusMap[$k->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst(str_replace('_', ' ', $k->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-slate-400 text-sm">
                        Belum ada kunjungan hari ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection