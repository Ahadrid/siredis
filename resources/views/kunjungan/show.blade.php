@extends('layouts.app')
@section('title', 'Detail Kunjungan')
@section('subtitle', $kunjungan->no_kunjungan)

@section('content')

<div class="grid grid-cols-3 gap-5">

    {{-- ══════════════ KOLOM KIRI: Info Kunjungan ══════════════ --}}
    <div class="col-span-1 space-y-4">

        {{-- Card Info --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800 text-sm">Info Kunjungan</h3>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">No. Kunjungan</p>
                    <p class="font-semibold mono text-sm text-slate-800">{{ $kunjungan->no_kunjungan }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">Pasien</p>
                    <a href="{{ route('pasien.show', $kunjungan->pasien) }}"
                       class="font-medium text-teal-600 hover:underline text-sm">
                        {{ $kunjungan->pasien->nama }}
                    </a>
                    <p class="text-xs text-slate-400 mono">{{ $kunjungan->pasien->no_rm }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">Dokter</p>
                    <p class="font-medium text-slate-800 text-sm">{{ $kunjungan->dokter->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">Tanggal & Waktu</p>
                    <p class="font-medium text-slate-800 text-sm mono">
                        {{ $kunjungan->tanggal_kunjungan->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-1">Status</p>
                    @php
                        $statusClass = [
                            'menunggu'     => 'bg-amber-50 text-amber-700 border-amber-200',
                            'dalam_proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'selesai'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'batal'        => 'bg-red-50 text-red-600 border-red-200',
                        ][$kunjungan->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    @endphp
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $kunjungan->status)) }}
                    </span>
                </div>
                <div class="pt-2 border-t border-slate-100">
                    <p class="text-xs text-slate-500 mb-1">Keluhan</p>
                    <p class="text-sm text-slate-700">{{ $kunjungan->keluhan }}</p>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        @can('edit kunjungan')
        @if(!in_array($kunjungan->status, ['selesai', 'batal']))
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-600 mb-3">Update Status</p>
            <div class="space-y-2">
                @if($kunjungan->status === 'menunggu')
                <form method="POST" action="{{ route('kunjungan.status', $kunjungan) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="dalam_proses">
                    <button type="submit"
                            class="w-full px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-medium transition">
                        → Tandai Dalam Proses
                    </button>
                </form>
                @endif
                @if(in_array($kunjungan->status, ['menunggu', 'dalam_proses']))
                <form method="POST" action="{{ route('kunjungan.status', $kunjungan) }}"
                      onsubmit="return confirm('Batalkan kunjungan ini?')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="batal">
                    <button type="submit"
                            class="w-full px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-xs font-medium transition">
                        ✕ Batalkan Kunjungan
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif
        @endcan

        {{-- Back --}}
        <a href="{{ route('kunjungan.index') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- ══════════════ KOLOM KANAN: Rekam Medis ══════════════ --}}
    <div class="col-span-2">
        @if($kunjungan->rekamMedis)

            {{-- Sudah ada rekam medis --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div>
                        <h3 class="font-semibold text-slate-800 text-sm">Rekam Medis</h3>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Diperiksa oleh {{ $kunjungan->rekamMedis->dokter->name }}
                            · {{ $kunjungan->rekamMedis->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @can('update', $kunjungan->rekamMedis)
                    <button type="button"
                            onclick="bukaModalEdit({{ $kunjungan->rekamMedis->id }})"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-blue-50 text-blue-600 border border-blue-200 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>
                    @endcan
                </div>

                <div class="p-5 space-y-5">

                    {{-- Tanda Vital --}}
                    @php $rm = $kunjungan->rekamMedis; @endphp
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Tanda Vital</p>
                        <div class="grid grid-cols-5 gap-3">
                            @foreach([
                                ['label'=>'TD (mmHg)',  'val'=>$rm->tekanan_darah],
                                ['label'=>'Suhu (°C)',  'val'=>$rm->suhu],
                                ['label'=>'Nadi (/mnt)','val'=>$rm->nadi],
                                ['label'=>'Respirasi',  'val'=>$rm->respirasi],
                                ['label'=>'BB (kg)',    'val'=>$rm->berat_badan],
                            ] as $v)
                            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                                <p class="text-base font-bold text-slate-800 mono">{{ $v['val'] ?? '—' }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $v['label'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Anamnesis & Diagnosis --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Anamnesis</p>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $rm->anamnesis }}</p>
                        </div>
                        <div class="bg-teal-50 rounded-xl p-4 border border-teal-100">
                            <p class="text-xs font-semibold text-teal-500 uppercase tracking-wider mb-2">Diagnosis</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $rm->diagnosis }}</p>
                            @if($rm->kode_icd)
                            <span class="inline-flex items-center px-2 py-0.5 bg-white text-slate-500 rounded mono text-xs mt-2 border border-teal-100">
                                ICD-10: {{ $rm->kode_icd }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Pemeriksaan Fisik & Tindakan --}}
                    @if($rm->pemeriksaan_fisik || $rm->tindakan)
                    <div class="grid grid-cols-2 gap-4">
                        @if($rm->pemeriksaan_fisik)
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pemeriksaan Fisik</p>
                            <p class="text-sm text-slate-700 bg-slate-50 rounded-xl p-4 border border-slate-100">
                                {{ $rm->pemeriksaan_fisik }}
                            </p>
                        </div>
                        @endif
                        @if($rm->tindakan)
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Tindakan</p>
                            <p class="text-sm text-slate-700 bg-slate-50 rounded-xl p-4 border border-slate-100">
                                {{ $rm->tindakan }}
                            </p>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Resep --}}
                    @if($kunjungan->rekamMedis->reseps->count() > 0)
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Resep Obat</p>
                        <div class="space-y-2">
                            @foreach($kunjungan->rekamMedis->reseps as $i => $resep)
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ $i + 1 }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-800">{{ $resep->obat->nama_obat }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $resep->aturan_pakai }}</p>
                                    </div>
                                </div>
                                <span class="mono font-semibold text-slate-700">
                                    {{ $resep->jumlah }} {{ $resep->obat->satuan }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Catatan --}}
                    @if($rm->catatan)
                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">📋 Catatan Dokter</p>
                        <p class="text-sm text-amber-800">{{ $rm->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>

        @else

            {{-- Belum ada rekam medis --}}
            <div class="bg-white rounded-xl border border-dashed border-slate-300 flex flex-col items-center justify-center py-20">
                <svg class="w-14 h-14 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-slate-500 font-semibold text-sm">Belum ada rekam medis</p>
                <p class="text-slate-400 text-xs mt-1 mb-5">Rekam medis akan diisi oleh dokter pemeriksa</p>

                @can('tambah rekam medis')
                @if($kunjungan->status !== 'batal')
                <button type="button"
                        onclick="bukaModalCreate()"
                        class="flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-medium transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Isi Rekam Medis
                </button>
                @endif
                @endcan
            </div>

        @endif
    </div>
</div>


{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: CREATE REKAM MEDIS                                       --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
@can('tambah rekam medis')
<div id="modal-create-rm" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModalCreate()"></div>

    <div class="relative flex items-center justify-center min-h-full p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 text-sm">Isi Rekam Medis</h3>
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $kunjungan->pasien->nama }}
                            <span class="mono">· {{ $kunjungan->no_kunjungan }}</span>
                        </p>
                    </div>
                </div>
                <button onclick="tutupModalCreate()"
                        class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body: 2 Kolom --}}
            <form id="form-create-rm" class="flex-1 overflow-hidden flex">
                @csrf
                <input type="hidden" name="kunjungan_id" value="{{ $kunjungan->id }}">

                {{-- ── Kolom Kiri: Info Pasien + Tanda Vital ── --}}
                <div class="w-64 shrink-0 bg-slate-50 border-r border-slate-100 overflow-y-auto p-5 space-y-5">

                    {{-- Info Pasien --}}
                    @php $pasien = $kunjungan->pasien; @endphp
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Info Pasien</p>
                        <div class="space-y-2.5">
                            <div>
                                <p class="text-xs text-slate-500">Nama</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $pasien->nama }}</p>
                            </div>
                            <div class="flex gap-4">
                                <div>
                                    <p class="text-xs text-slate-500">No. RM</p>
                                    <p class="text-xs mono text-slate-600">{{ $pasien->no_rm }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Gol. Darah</p>
                                    <p class="text-sm font-bold text-red-600">{{ $pasien->golongan_darah }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Usia / JK</p>
                                <p class="text-sm text-slate-700">{{ $pasien->umur }} th / {{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            @if($pasien->riwayat_alergi)
                            <div class="bg-red-50 border border-red-100 rounded-lg p-2.5">
                                <p class="text-xs font-semibold text-red-500 mb-0.5">⚠️ Riwayat Alergi</p>
                                <p class="text-xs text-red-600">{{ $pasien->riwayat_alergi }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tanda Vital --}}
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Tanda Vital</p>
                        <div class="space-y-2.5">
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Tekanan Darah</label>
                                <input type="text" name="tekanan_darah"
                                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono bg-white"
                                       placeholder="120/80">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-slate-500 mb-1">Suhu °C</label>
                                    <input type="number" name="suhu" step="0.1"
                                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono bg-white"
                                           placeholder="36.5">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-500 mb-1">Nadi</label>
                                    <input type="number" name="nadi"
                                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono bg-white"
                                           placeholder="80">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-slate-500 mb-1">Respirasi</label>
                                    <input type="number" name="respirasi"
                                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono bg-white"
                                           placeholder="20">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-500 mb-1">BB (kg)</label>
                                    <input type="number" name="berat_badan" step="0.1"
                                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono bg-white"
                                           placeholder="60">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Kolom Kanan: Form Klinis ── --}}
                <div class="flex-1 overflow-y-auto p-5 space-y-4">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Anamnesis <span class="text-red-500">*</span>
                        </label>
                        <textarea name="anamnesis" rows="3"
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                                  placeholder="Keluhan utama pasien secara detail...">{{ $kunjungan->keluhan }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pemeriksaan Fisik</label>
                        <textarea name="pemeriksaan_fisik" rows="2"
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                                  placeholder="Hasil pemeriksaan fisik..."></textarea>
                    </div>

                    <div class="grid grid-cols-4 gap-3">
                        <div class="col-span-3">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Diagnosis <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="diagnosis"
                                   class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                                   placeholder="Nama penyakit / kondisi">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kode ICD-10</label>
                            <input type="text" name="kode_icd"
                                   class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono uppercase"
                                   placeholder="A00.0">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tindakan</label>
                        <textarea name="tindakan" rows="2"
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                                  placeholder="Tindakan medis yang dilakukan..."></textarea>
                    </div>

                    {{-- ── Resep Obat (Dinamis) ── --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-slate-600">Resep Obat</label>
                            <button type="button"
                                    onclick="tambahResepModal()"
                                    class="flex items-center gap-1.5 px-2.5 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 rounded-lg text-xs font-medium transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Obat
                            </button>
                        </div>
                        <div id="resep-container-modal" class="space-y-2"></div>
                        <p id="resep-empty-modal"
                           class="text-xs text-slate-400 italic text-center py-3 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            Belum ada obat. Klik "+ Tambah Obat" untuk menambahkan resep.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan</label>
                        <textarea name="catatan" rows="2"
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                                  placeholder="Saran atau catatan untuk pasien..."></textarea>
                    </div>

                    {{-- Error Box --}}
                    <div id="create-errors" class="hidden px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-xs font-semibold text-red-600 mb-1">Terjadi kesalahan validasi:</p>
                        <ul id="create-error-list" class="text-xs text-red-600 space-y-0.5 list-disc list-inside"></ul>
                    </div>
                </div>
            </form>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3 shrink-0">
                <button type="button"
                        onclick="tutupModalCreate()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-sm font-medium transition">
                    Batal
                </button>
                <button type="button"
                        onclick="submitCreateRM()"
                        id="btn-simpan-create"
                        class="flex items-center gap-2 px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Rekam Medis
                </button>
            </div>
        </div>
    </div>
</div>
@endcan


{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: EDIT REKAM MEDIS                                         --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
@if($kunjungan->rekamMedis)
@can('update', $kunjungan->rekamMedis)
<div id="modal-edit-rm" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModalEdit()"></div>

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
                        <p class="text-xs text-slate-400 mt-0.5">{{ $kunjungan->pasien->nama }}</p>
                    </div>
                </div>
                <button onclick="tutupModalEdit()"
                        class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-400 hover:text-slate-600">
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
            <form id="form-edit-rm" class="flex-1 overflow-y-auto hidden">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit-rm-id">

                <div class="p-6 space-y-4">

                    {{-- Tanda Vital --}}
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Tanda Vital</p>
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

                    <div class="border-t border-slate-100 pt-4 space-y-3">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Klinis</p>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">
                                Anamnesis <span class="text-red-500">*</span>
                            </label>
                            <textarea name="anamnesis" id="e-anamnesis" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Pemeriksaan Fisik</label>
                            <textarea name="pemeriksaan_fisik" id="e-pemeriksaan_fisik" rows="2"
                                      class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-4 gap-3">
                            <div class="col-span-3">
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                                    Diagnosis <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="diagnosis" id="e-diagnosis"
                                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">Kode ICD</label>
                                <input type="text" name="kode_icd" id="e-kode_icd"
                                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono uppercase">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Tindakan</label>
                            <textarea name="tindakan" id="e-tindakan" rows="2"
                                      class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Catatan</label>
                            <textarea name="catatan" id="e-catatan" rows="2"
                                      class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                        </div>
                    </div>

                    {{-- Error Box --}}
                    <div id="edit-errors" class="hidden px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-xs font-semibold text-red-600 mb-1">Terjadi kesalahan:</p>
                        <ul id="edit-error-list" class="text-xs text-red-600 space-y-0.5 list-disc list-inside"></ul>
                    </div>
                </div>
            </form>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3 shrink-0">
                <button type="button"
                        onclick="tutupModalEdit()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-sm font-medium transition">
                    Batal
                </button>
                <button type="button"
                        onclick="submitEditRM()"
                        id="btn-simpan-edit"
                        class="flex items-center gap-2 px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
@endcan
@endif


{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- TOAST NOTIFIKASI                                                 --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<div id="toast" class="fixed bottom-6 right-6 z-60 hidden transition-all duration-300">
    <div id="toast-inner" class="flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-xl text-sm font-medium min-w-70">
        <svg id="toast-icon" class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"></svg>
        <span id="toast-msg"></span>
    </div>
</div>


@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ────────────────────────────────────────────
// DATA OBAT (dari server, hanya stok > 0)
// ────────────────────────────────────────────
const daftarObat = @json(\App\Models\Obat::where('stok', '>', 0)->orderBy('nama_obat')->get(['id','nama_obat','satuan','stok']));
let resepCount = 0;

// ────────────────────────────────────────────
// TOAST
// ────────────────────────────────────────────
function tampilToast(pesan, tipe = 'success') {
    const inner = document.getElementById('toast-inner');
    const msg   = document.getElementById('toast-msg');
    const icon  = document.getElementById('toast-icon');
    msg.textContent = pesan;
    if (tipe === 'success') {
        inner.className = 'flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-xl text-sm font-medium min-w-[280px] bg-emerald-600 text-white';
        icon.innerHTML  = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>';
    } else {
        inner.className = 'flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-xl text-sm font-medium min-w-[280px] bg-red-600 text-white';
        icon.innerHTML  = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
    }
    document.getElementById('toast').classList.remove('hidden');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => document.getElementById('toast').classList.add('hidden'), 3500);
}

// ────────────────────────────────────────────
// MODAL CREATE
// ────────────────────────────────────────────
function bukaModalCreate() {
    document.getElementById('modal-create-rm').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function tutupModalCreate() {
    document.getElementById('modal-create-rm').classList.add('hidden');
    document.body.style.overflow = '';
}

function tambahResepModal() {
    resepCount++;
    document.getElementById('resep-empty-modal').classList.add('hidden');

    const opts = daftarObat.map(o =>
        `<option value="${o.id}">${o.nama_obat} — stok: ${o.stok} ${o.satuan}</option>`
    ).join('');

    const html = `
    <div class="flex items-center gap-2 p-2.5 bg-slate-50 rounded-lg border border-slate-200" id="item-resep-${resepCount}">
        <div class="flex-1 grid grid-cols-3 gap-2">
            <div>
                <select name="resep[${resepCount}][obat_id]"
                        class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                    ${opts}
                </select>
            </div>
            <div>
                <input type="number" name="resep[${resepCount}][jumlah]"
                       min="1" value="1"
                       class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono"
                       placeholder="Jumlah">
            </div>
            <div>
                <input type="text" name="resep[${resepCount}][aturan_pakai]"
                       class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                       placeholder="3x1 sesudah makan">
            </div>
        </div>
        <button type="button"
                onclick="hapusResepModal(${resepCount})"
                class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>`;

    document.getElementById('resep-container-modal').insertAdjacentHTML('beforeend', html);
}

function hapusResepModal(id) {
    document.getElementById(`item-resep-${id}`).remove();
    const container = document.getElementById('resep-container-modal');
    if (!container.children.length) {
        document.getElementById('resep-empty-modal').classList.remove('hidden');
    }
}

async function submitCreateRM() {
    const btn   = document.getElementById('btn-simpan-create');
    const errEl = document.getElementById('create-errors');

    btn.disabled = true;
    btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Menyimpan...`;
    errEl.classList.add('hidden');

    try {
        const res  = await fetch('{{ route("rekam-medis.store") }}', {
            method:  'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    new FormData(document.getElementById('form-create-rm')),
        });
        const data = await res.json();

        if (res.ok && data.success) {
            tutupModalCreate();
            tampilToast('Rekam medis berhasil disimpan!');
            setTimeout(() => location.reload(), 1400);
        } else {
            const list = document.getElementById('create-error-list');
            list.innerHTML = '';
            if (data.errors) {
                Object.values(data.errors).flat().forEach(m => {
                    list.innerHTML += `<li>${m}</li>`;
                });
            } else {
                list.innerHTML = `<li>${data.message ?? 'Terjadi kesalahan.'}</li>`;
            }
            errEl.classList.remove('hidden');
        }
    } catch (err) {
        tampilToast('Gagal terhubung ke server.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Rekam Medis`;
    }
}

// ────────────────────────────────────────────
// MODAL EDIT
// ────────────────────────────────────────────
function bukaModalEdit(rmId) {
    document.getElementById('modal-edit-rm').classList.remove('hidden');
    document.getElementById('edit-loading').classList.remove('hidden');
    document.getElementById('form-edit-rm').classList.add('hidden');
    document.getElementById('edit-errors').classList.add('hidden');
    document.body.style.overflow = 'hidden';

    fetch(`/rekam-medis/${rmId}/edit`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(data => {
        const rm = data.rekamMedis;
        document.getElementById('edit-rm-id').value             = rm.id;
        document.getElementById('e-tekanan_darah').value        = rm.tekanan_darah  ?? '';
        document.getElementById('e-suhu').value                 = rm.suhu           ?? '';
        document.getElementById('e-nadi').value                 = rm.nadi           ?? '';
        document.getElementById('e-respirasi').value            = rm.respirasi      ?? '';
        document.getElementById('e-berat_badan').value          = rm.berat_badan    ?? '';
        document.getElementById('e-anamnesis').value            = rm.anamnesis      ?? '';
        document.getElementById('e-pemeriksaan_fisik').value    = rm.pemeriksaan_fisik ?? '';
        document.getElementById('e-diagnosis').value            = rm.diagnosis      ?? '';
        document.getElementById('e-kode_icd').value             = rm.kode_icd       ?? '';
        document.getElementById('e-tindakan').value             = rm.tindakan       ?? '';
        document.getElementById('e-catatan').value              = rm.catatan        ?? '';

        document.getElementById('edit-loading').classList.add('hidden');
        document.getElementById('form-edit-rm').classList.remove('hidden');
    })
    .catch(() => {
        tutupModalEdit();
        tampilToast('Gagal memuat data untuk edit.', 'error');
    });
}

function tutupModalEdit() {
    document.getElementById('modal-edit-rm').classList.add('hidden');
    document.body.style.overflow = '';
}

async function submitEditRM() {
    const rmId  = document.getElementById('edit-rm-id').value;
    const btn   = document.getElementById('btn-simpan-edit');
    const errEl = document.getElementById('edit-errors');

    btn.disabled = true;
    btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Menyimpan...`;
    errEl.classList.add('hidden');

    try {
        const res  = await fetch(`/rekam-medis/${rmId}`, {
            method:  'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    new FormData(document.getElementById('form-edit-rm')),
        });
        const data = await res.json();

        if (res.ok && data.success) {
            tutupModalEdit();
            tampilToast('Rekam medis berhasil diperbarui!');
            setTimeout(() => location.reload(), 1400);
        } else {
            const list = document.getElementById('edit-error-list');
            list.innerHTML = '';
            if (data.errors) {
                Object.values(data.errors).flat().forEach(m => {
                    list.innerHTML += `<li>${m}</li>`;
                });
            } else {
                list.innerHTML = `<li>${data.message ?? 'Terjadi kesalahan.'}</li>`;
            }
            errEl.classList.remove('hidden');
        }
    } catch (err) {
        tampilToast('Gagal terhubung ke server.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Perubahan`;
    }
}

// ────────────────────────────────────────────
// TUTUP MODAL DENGAN ESCAPE
// ────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (!document.getElementById('modal-create-rm')?.classList.contains('hidden')) tutupModalCreate();
    if (!document.getElementById('modal-edit-rm')?.classList.contains('hidden'))   tutupModalEdit();
});
</script>
@endpush

@endsection