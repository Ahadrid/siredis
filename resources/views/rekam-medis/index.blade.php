@extends('layouts.app')
@section('title', 'Rekam Medis')
@section('subtitle', 'Riwayat pemeriksaan & diagnosis pasien')

@section('content')

{{-- ══════════════ FILTER & HEADER ══════════════ --}}
<div class="flex items-center justify-between mb-5 gap-3 flex-wrap">
    <form method="GET" class="flex items-center gap-2 flex-wrap">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari pasien / diagnosis..."
                   class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 w-60 bg-white">
        </div>

        <select name="dokter_id" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
            <option value="">Semua Dokter</option>
            @foreach($dokters as $dokter)
            <option value="{{ $dokter->id }}" {{ request('dokter_id') == $dokter->id ? 'selected' : '' }}>
                {{ $dokter->name }}
            </option>
            @endforeach
        </select>

        <input type="date" name="dari" value="{{ request('dari') }}"
               class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white mono"
               title="Dari tanggal">
        <span class="text-slate-400 text-xs">s/d</span>
        <input type="date" name="sampai" value="{{ request('sampai') }}"
               class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white mono"
               title="Sampai tanggal">

        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
            Filter
        </button>
        @if(request()->hasAny(['search','dokter_id','dari','sampai']))
        <a href="{{ route('rekam-medis.index') }}" class="text-slate-400 hover:text-slate-600 text-sm px-1 transition">✕ Reset</a>
        @endif
    </form>

    <div class="text-xs text-slate-400">
        Total: <span class="font-semibold text-slate-600">{{ $rekamMedis->total() }}</span> rekam medis
    </div>
</div>

{{-- ══════════════ TABLE ══════════════ --}}
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Pasien</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Dokter</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Diagnosis</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanda Vital</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rekamMedis as $rm)
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-700 font-bold text-xs shrink-0">
                                {{ strtoupper(substr($rm->pasien->nama, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800 leading-tight">{{ $rm->pasien->nama }}</p>
                                <p class="text-xs text-slate-400 mono mt-0.5">{{ $rm->pasien->no_rm }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 text-sm">{{ $rm->dokter->name }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-slate-800 text-sm leading-tight">{{ $rm->diagnosis }}</p>
                        @if($rm->kode_icd)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 mono text-xs mt-1">
                            {{ $rm->kode_icd }}
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex flex-col gap-0.5">
                            @if($rm->tekanan_darah)
                            <span class="text-xs text-slate-500 mono">TD: {{ $rm->tekanan_darah }}</span>
                            @endif
                            @if($rm->suhu)
                            <span class="text-xs text-slate-500 mono">S: {{ $rm->suhu }}°C</span>
                            @endif
                            @if(!$rm->tekanan_darah && !$rm->suhu)
                            <span class="text-xs text-slate-300">—</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs mono whitespace-nowrap">
                        {{ $rm->created_at->format('d/m/Y') }}<br>
                        <span class="text-slate-300">{{ $rm->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1 justify-end">
                            {{-- Tombol View --}}
                            <button type="button"
                                    onclick="bukaModalView({{ $rm->id }})"
                                    class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition"
                                    title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>

                            {{-- Tombol Edit (hanya dokter pemilik atau admin) --}}
                            @can('update', $rm)
                            <button type="button"
                                    onclick="bukaModalEdit({{ $rm->id }})"
                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                    title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-20 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-slate-500 font-medium text-sm">Belum ada rekam medis</p>
                            <p class="text-slate-400 text-xs">Rekam medis dibuat melalui menu Kunjungan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rekamMedis->hasPages())
    <div class="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between">
        <p class="text-xs text-slate-400">
            Menampilkan <span class="font-medium text-slate-600">{{ $rekamMedis->firstItem() }}–{{ $rekamMedis->lastItem() }}</span>
            dari <span class="font-medium text-slate-600">{{ $rekamMedis->total() }}</span> rekam medis
        </p>
        {{ $rekamMedis->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- MODAL: VIEW DETAIL REKAM MEDIS                              --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div id="modal-view" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModal('modal-view')"></div>

    {{-- Panel --}}
    <div class="relative flex items-center justify-center min-h-full p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 text-sm">Detail Rekam Medis</h3>
                        <p id="view-subtitle" class="text-xs text-slate-400 mt-0.5"></p>
                    </div>
                </div>
                <button onclick="tutupModal('modal-view')" class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Loading --}}
            <div id="view-loading" class="flex-1 flex items-center justify-center py-16">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                    <svg class="w-6 h-6 animate-spin text-teal-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span class="text-sm">Memuat data...</span>
                </div>
            </div>

            {{-- Content --}}
            <div id="view-content" class="flex-1 overflow-y-auto hidden">

                {{-- Info Bar --}}
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center gap-6">
                    <div>
                        <p class="text-xs text-slate-500">Pasien</p>
                        <p id="v-pasien-nama" class="font-semibold text-slate-800 text-sm"></p>
                        <p id="v-pasien-rm" class="text-xs text-slate-400 mono"></p>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div>
                        <p class="text-xs text-slate-500">Dokter</p>
                        <p id="v-dokter" class="font-medium text-slate-700 text-sm"></p>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div>
                        <p class="text-xs text-slate-500">Tanggal</p>
                        <p id="v-tanggal" class="font-medium text-slate-700 text-sm mono"></p>
                    </div>
                    <div class="ml-auto">
                        <span id="v-kode-icd" class="hidden items-center px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg mono text-xs font-medium"></span>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Tanda Vital --}}
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Tanda Vital</p>
                        <div class="grid grid-cols-5 gap-3">
                            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                                <p id="v-td" class="text-base font-bold text-slate-800 mono">—</p>
                                <p class="text-xs text-slate-400 mt-1">TD (mmHg)</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                                <p id="v-suhu" class="text-base font-bold text-slate-800 mono">—</p>
                                <p class="text-xs text-slate-400 mt-1">Suhu (°C)</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                                <p id="v-nadi" class="text-base font-bold text-slate-800 mono">—</p>
                                <p class="text-xs text-slate-400 mt-1">Nadi (/mnt)</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                                <p id="v-respirasi" class="text-base font-bold text-slate-800 mono">—</p>
                                <p class="text-xs text-slate-400 mt-1">Respirasi</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                                <p id="v-bb" class="text-base font-bold text-slate-800 mono">—</p>
                                <p class="text-xs text-slate-400 mt-1">BB (kg)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Anamnesis & Diagnosis --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Anamnesis</p>
                            <p id="v-anamnesis" class="text-sm text-slate-700 leading-relaxed"></p>
                        </div>
                        <div class="bg-teal-50 rounded-xl p-4 border border-teal-100">
                            <p class="text-xs font-semibold text-teal-500 uppercase tracking-wider mb-2">Diagnosis</p>
                            <p id="v-diagnosis" class="text-sm font-semibold text-slate-800 leading-relaxed"></p>
                        </div>
                    </div>

                    {{-- Pemeriksaan Fisik --}}
                    <div id="v-pemfis-wrap" class="hidden">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pemeriksaan Fisik</p>
                        <p id="v-pemfis" class="text-sm text-slate-700 leading-relaxed bg-slate-50 rounded-xl p-4 border border-slate-100"></p>
                    </div>

                    {{-- Tindakan --}}
                    <div id="v-tindakan-wrap" class="hidden">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Tindakan</p>
                        <p id="v-tindakan" class="text-sm text-slate-700 leading-relaxed bg-slate-50 rounded-xl p-4 border border-slate-100"></p>
                    </div>

                    {{-- Resep --}}
                    <div id="v-resep-wrap">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Resep Obat</p>
                        <div id="v-resep-list" class="space-y-2"></div>
                        <p id="v-resep-empty" class="text-xs text-slate-400 italic hidden">Tidak ada resep obat</p>
                    </div>

                    {{-- Catatan --}}
                    <div id="v-catatan-wrap" class="hidden">
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">📋 Catatan Dokter</p>
                            <p id="v-catatan" class="text-sm text-amber-800"></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- MODAL: EDIT REKAM MEDIS                                     --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModal('modal-edit')"></div>

    <div class="relative flex items-center justify-center min-h-full p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 text-sm">Edit Rekam Medis</h3>
                        <p id="edit-subtitle" class="text-xs text-slate-400 mt-0.5"></p>
                    </div>
                </div>
                <button onclick="tutupModal('modal-edit')" class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Loading --}}
            <div id="edit-loading" class="flex-1 flex items-center justify-center py-16">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                    <svg class="w-6 h-6 animate-spin text-teal-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span class="text-sm">Memuat data...</span>
                </div>
            </div>

            {{-- Form --}}
            <form id="form-edit" class="flex-1 overflow-y-auto hidden">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit-id" name="edit_id">

                <div class="p-6 space-y-4">

                    {{-- Tanda Vital --}}
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Tanda Vital</p>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Tekanan Darah</label>
                                <input type="text" name="tekanan_darah" id="e-tekanan_darah"
                                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono"
                                       placeholder="120/80">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Suhu (°C)</label>
                                <input type="number" name="suhu" id="e-suhu" step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono"
                                       placeholder="36.5">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Nadi (/mnt)</label>
                                <input type="number" name="nadi" id="e-nadi"
                                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Respirasi (/mnt)</label>
                                <input type="number" name="respirasi" id="e-respirasi"
                                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Berat Badan (kg)</label>
                                <input type="number" name="berat_badan" id="e-berat_badan" step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Klinis</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-slate-600 font-medium mb-1">Anamnesis <span class="text-red-500">*</span></label>
                                <textarea name="anamnesis" id="e-anamnesis" rows="2"
                                          class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-600 font-medium mb-1">Pemeriksaan Fisik</label>
                                <textarea name="pemeriksaan_fisik" id="e-pemeriksaan_fisik" rows="2"
                                          class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-4 gap-3">
                                <div class="col-span-3">
                                    <label class="block text-xs text-slate-600 font-medium mb-1">Diagnosis <span class="text-red-500">*</span></label>
                                    <input type="text" name="diagnosis" id="e-diagnosis"
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-600 font-medium mb-1">Kode ICD</label>
                                    <input type="text" name="kode_icd" id="e-kode_icd"
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono uppercase">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-600 font-medium mb-1">Tindakan</label>
                                <textarea name="tindakan" id="e-tindakan" rows="2"
                                          class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-600 font-medium mb-1">Catatan</label>
                                <textarea name="catatan" id="e-catatan" rows="2"
                                          class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Error container --}}
                    <div id="edit-errors" class="hidden px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-xs font-semibold text-red-600 mb-1">Terjadi kesalahan:</p>
                        <ul id="edit-error-list" class="text-xs text-red-600 space-y-0.5 list-disc list-inside"></ul>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0 bg-slate-50">
                    <button type="button" onclick="tutupModal('modal-edit')"
                            class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-sm font-medium transition">
                        Batal
                    </button>
                    <button type="submit" id="btn-simpan-edit"
                            class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- MODAL: BUAT REKAM MEDIS (dari kunjungan)                    --}}
{{-- Dipanggil dari halaman kunjungan via URL param              --}}
{{-- ════════════════════════════════════════════════════════════ --}}

{{-- Toast Notifikasi --}}
<div id="toast" class="fixed bottom-6 right-6 z-60 hidden">
    <div id="toast-inner" class="flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium min-w-65">
        <svg id="toast-icon" class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"></svg>
        <span id="toast-msg"></span>
    </div>
</div>

@endsection


@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ──────────────────────────────────────────────
// UTILS
// ──────────────────────────────────────────────
function bukaModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // Animasi masuk
    requestAnimationFrame(() => {
        el.querySelector('.relative > div:last-child, .relative > div').style.transform = 'translateY(0)';
    });
}

function tutupModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
}

function tampilToast(pesan, tipe = 'success') {
    const toast     = document.getElementById('toast');
    const inner     = document.getElementById('toast-inner');
    const msg       = document.getElementById('toast-msg');
    const icon      = document.getElementById('toast-icon');

    msg.textContent = pesan;

    if (tipe === 'success') {
        inner.className = 'flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium min-w-[260px] bg-emerald-600 text-white';
        icon.innerHTML  = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>';
    } else {
        inner.className = 'flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium min-w-[260px] bg-red-600 text-white';
        icon.innerHTML  = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
    }

    toast.classList.remove('hidden');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => toast.classList.add('hidden'), 3500);
}

function isi(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val || '—';
}

// ──────────────────────────────────────────────
// MODAL VIEW
// ──────────────────────────────────────────────
async function bukaModalView(rmId) {
    bukaModal('modal-view');
    document.getElementById('view-loading').classList.remove('hidden');
    document.getElementById('view-content').classList.add('hidden');

    try {
        const res  = await fetch(`/rekam-medis/${rmId}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();

        // Subtitle
        document.getElementById('view-subtitle').textContent =
            `${data.pasien?.nama} · ${new Date(data.created_at).toLocaleDateString('id-ID')}`;

        // Info bar
        isi('v-pasien-nama', data.pasien?.nama);
        isi('v-pasien-rm',   data.pasien?.no_rm);
        isi('v-dokter',      data.dokter?.name);
        isi('v-tanggal',     new Date(data.created_at).toLocaleString('id-ID', {day:'2-digit',month:'2-digit',year:'numeric',hour:'2-digit',minute:'2-digit'}));

        // Kode ICD
        const icdEl = document.getElementById('v-kode-icd');
        if (data.kode_icd) {
            icdEl.textContent = 'ICD: ' + data.kode_icd;
            icdEl.classList.remove('hidden');
        } else { icdEl.classList.add('hidden'); }

        // Vital signs
        isi('v-td',       data.tekanan_darah);
        isi('v-suhu',     data.suhu ? data.suhu + '°C' : null);
        isi('v-nadi',     data.nadi);
        isi('v-respirasi',data.respirasi);
        isi('v-bb',       data.berat_badan ? data.berat_badan + ' kg' : null);

        // Klinis
        isi('v-anamnesis',  data.anamnesis);
        isi('v-diagnosis',  data.diagnosis);

        const pemfis = document.getElementById('v-pemfis-wrap');
        if (data.pemeriksaan_fisik) {
            isi('v-pemfis', data.pemeriksaan_fisik);
            pemfis.classList.remove('hidden');
        } else { pemfis.classList.add('hidden'); }

        const tindakan = document.getElementById('v-tindakan-wrap');
        if (data.tindakan) {
            isi('v-tindakan', data.tindakan);
            tindakan.classList.remove('hidden');
        } else { tindakan.classList.add('hidden'); }

        const catatan = document.getElementById('v-catatan-wrap');
        if (data.catatan) {
            isi('v-catatan', data.catatan);
            catatan.classList.remove('hidden');
        } else { catatan.classList.add('hidden'); }

        // Resep
        const resepList  = document.getElementById('v-resep-list');
        const resepEmpty = document.getElementById('v-resep-empty');
        resepList.innerHTML = '';

        if (data.reseps && data.reseps.length > 0) {
            resepEmpty.classList.add('hidden');
            data.reseps.forEach((r, i) => {
                resepList.innerHTML += `
                <div class="flex items-center justify-between bg-slate-50 border border-slate-100 rounded-xl px-4 py-3">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold flex-shrink-0">${i+1}</span>
                        <div>
                            <p class="text-sm font-medium text-slate-800">${r.obat?.nama_obat ?? '-'}</p>
                            <p class="text-xs text-slate-500 mt-0.5">${r.aturan_pakai}</p>
                        </div>
                    </div>
                    <span class="mono font-semibold text-slate-700 text-sm">${r.jumlah} ${r.obat?.satuan ?? ''}</span>
                </div>`;
            });
        } else {
            resepEmpty.classList.remove('hidden');
        }

        document.getElementById('view-loading').classList.add('hidden');
        document.getElementById('view-content').classList.remove('hidden');

    } catch (e) {
        tutupModal('modal-view');
        tampilToast('Gagal memuat data rekam medis.', 'error');
    }
}

// ──────────────────────────────────────────────
// MODAL EDIT
// ──────────────────────────────────────────────
async function bukaModalEdit(rmId) {
    bukaModal('modal-edit');
    document.getElementById('edit-loading').classList.remove('hidden');
    document.getElementById('form-edit').classList.add('hidden');
    document.getElementById('edit-errors').classList.add('hidden');

    try {
        const res  = await fetch(`/rekam-medis/${rmId}/edit`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        const rm   = data.rekamMedis;

        document.getElementById('edit-id').value              = rm.id;
        document.getElementById('edit-subtitle').textContent  = rm.kunjungan?.pasien?.nama ?? '';
        document.getElementById('e-tekanan_darah').value      = rm.tekanan_darah ?? '';
        document.getElementById('e-suhu').value               = rm.suhu ?? '';
        document.getElementById('e-nadi').value               = rm.nadi ?? '';
        document.getElementById('e-respirasi').value          = rm.respirasi ?? '';
        document.getElementById('e-berat_badan').value        = rm.berat_badan ?? '';
        document.getElementById('e-anamnesis').value          = rm.anamnesis ?? '';
        document.getElementById('e-pemeriksaan_fisik').value  = rm.pemeriksaan_fisik ?? '';
        document.getElementById('e-diagnosis').value          = rm.diagnosis ?? '';
        document.getElementById('e-kode_icd').value           = rm.kode_icd ?? '';
        document.getElementById('e-tindakan').value           = rm.tindakan ?? '';
        document.getElementById('e-catatan').value            = rm.catatan ?? '';

        document.getElementById('edit-loading').classList.add('hidden');
        document.getElementById('form-edit').classList.remove('hidden');

    } catch (e) {
        tutupModal('modal-edit');
        tampilToast('Gagal memuat data untuk edit.', 'error');
    }
}

// Submit form edit
document.getElementById('form-edit').addEventListener('submit', async function(e) {
    e.preventDefault();
    const rmId  = document.getElementById('edit-id').value;
    const btn   = document.getElementById('btn-simpan-edit');
    const errEl = document.getElementById('edit-errors');

    btn.disabled    = true;
    btn.textContent = 'Menyimpan...';
    errEl.classList.add('hidden');

    const formData = new FormData(this);

    try {
        const res  = await fetch(`/rekam-medis/${rmId}`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: formData,
        });
        const data = await res.json();

        if (res.ok && data.success) {
            tutupModal('modal-edit');
            tampilToast('Rekam medis berhasil diperbarui!');
            setTimeout(() => location.reload(), 1200);
        } else {
            // Tampilkan validation errors
            const errorList = document.getElementById('edit-error-list');
            errorList.innerHTML = '';
            if (data.errors) {
                Object.values(data.errors).flat().forEach(msg => {
                    errorList.innerHTML += `<li>${msg}</li>`;
                });
            }
            errEl.classList.remove('hidden');
        }
    } catch (err) {
        tampilToast('Terjadi kesalahan. Coba lagi.', 'error');
    } finally {
        btn.disabled    = false;
        btn.innerHTML   = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Perubahan`;
    }
});

// Tutup modal dengan Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['modal-view', 'modal-edit'].forEach(id => {
            if (!document.getElementById(id).classList.contains('hidden')) {
                tutupModal(id);
            }
        });
    }
});
</script>
@endpush