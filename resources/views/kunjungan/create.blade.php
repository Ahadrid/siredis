@extends('layouts.app')
@section('title', 'Isi Rekam Medis')
@section('subtitle', $kunjungan->no_kunjungan . ' — ' . $kunjungan->pasien->nama)

@section('content')

<form method="POST" action="{{ route('rekam-medis.store') }}">
@csrf
<input type="hidden" name="kunjungan_id" value="{{ $kunjungan->id }}">

<div class="grid grid-cols-3 gap-5">

    {{-- Kolom Kiri: Info Pasien --}}
    <div class="col-span-1 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800 text-sm">Info Pasien</h3>
            </div>
            <div class="p-5 space-y-2.5">
                @php $pasien = $kunjungan->pasien; @endphp
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Nama</span>
                    <span class="font-medium text-slate-800">{{ $pasien->nama }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">No. RM</span>
                    <span class="mono text-slate-700">{{ $pasien->no_rm }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Usia/JK</span>
                    <span class="text-slate-700">{{ $pasien->umur }} th / {{ $pasien->jenis_kelamin }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Gol. Darah</span>
                    <span class="font-bold text-red-600">{{ $pasien->golongan_darah }}</span>
                </div>
                @if($pasien->riwayat_alergi)
                <div class="mt-2 pt-2 border-t border-slate-100">
                    <p class="text-xs text-red-500 font-semibold mb-1">⚠️ Alergi</p>
                    <p class="text-xs text-red-600">{{ $pasien->riwayat_alergi }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Tanda Vital --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800 text-sm">Tanda Vital</h3>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tekanan Darah</label>
                    <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah') }}"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono"
                           placeholder="120/80">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Suhu (°C)</label>
                        <input type="number" name="suhu" value="{{ old('suhu') }}" step="0.1"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono"
                               placeholder="36.5">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nadi (/mnt)</label>
                        <input type="number" name="nadi" value="{{ old('nadi') }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono"
                               placeholder="80">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Respirasi (/mnt)</label>
                        <input type="number" name="respirasi" value="{{ old('respirasi') }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono"
                               placeholder="20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">BB (kg)</label>
                        <input type="number" name="berat_badan" value="{{ old('berat_badan') }}" step="0.1"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono"
                               placeholder="60">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Tengah & Kanan: Form Utama --}}
    <div class="col-span-2 space-y-4">

        {{-- Anamnesis & Pemeriksaan --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800 text-sm">Anamnesis & Pemeriksaan</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Anamnesis (Keluhan) <span class="text-red-500">*</span></label>
                    <textarea name="anamnesis" rows="3"
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none @error('anamnesis') border-red-400 @enderror"
                              placeholder="Keluhan utama pasien secara detail...">{{ old('anamnesis', $kunjungan->keluhan) }}</textarea>
                    @error('anamnesis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pemeriksaan Fisik</label>
                    <textarea name="pemeriksaan_fisik" rows="3"
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                              placeholder="Hasil pemeriksaan fisik...">{{ old('pemeriksaan_fisik') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Diagnosis --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800 text-sm">Diagnosis & Tindakan</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Diagnosis <span class="text-red-500">*</span></label>
                        <input type="text" name="diagnosis" value="{{ old('diagnosis') }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('diagnosis') border-red-400 @enderror"
                               placeholder="Nama penyakit / kondisi">
                        @error('diagnosis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kode ICD-10</label>
                        <input type="text" name="kode_icd" value="{{ old('kode_icd') }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono uppercase"
                               placeholder="A00.0">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tindakan</label>
                    <textarea name="tindakan" rows="2"
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                              placeholder="Tindakan medis yang dilakukan...">{{ old('tindakan') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan Tambahan</label>
                    <textarea name="catatan" rows="2"
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                              placeholder="Catatan atau saran untuk pasien...">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Resep Obat (Dinamis) --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800 text-sm">Resep Obat</h3>
                <button type="button" id="btn-tambah-resep"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 rounded-lg text-xs font-medium transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Obat
                </button>
            </div>
            <div class="p-5">
                <div id="resep-container" class="space-y-3">
                    {{-- Item resep akan di-append di sini --}}
                </div>
                <p id="resep-empty" class="text-xs text-slate-400 text-center py-4">
                    Belum ada obat ditambahkan. Klik "Tambah Obat" untuk menambah resep.
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                Simpan Rekam Medis
            </button>
            <a href="{{ route('kunjungan.show', $kunjungan) }}"
               class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
                Batal
            </a>
        </div>
    </div>
</div>
</form>

@push('scripts')
<script>
const obats = @json($obats);
let resepCount = 0;

function tambahResep() {
    resepCount++;
    document.getElementById('resep-empty').classList.add('hidden');

    const optionsHtml = obats.map(o =>
        `<option value="${o.id}">${o.nama_obat} (Stok: ${o.stok} ${o.satuan})</option>`
    ).join('');

    const html = `
    <div class="resep-item flex items-start gap-2 p-3 bg-slate-50 rounded-lg border border-slate-200" id="resep-${resepCount}">
        <div class="flex-1 grid grid-cols-3 gap-2">
            <div class="col-span-1">
                <label class="block text-xs text-slate-500 mb-1">Obat</label>
                <select name="resep[${resepCount}][obat_id]" class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                    ${optionsHtml}
                </select>
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">Jumlah</label>
                <input type="number" name="resep[${resepCount}][jumlah]" min="1" value="1"
                       class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mono">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">Aturan Pakai</label>
                <input type="text" name="resep[${resepCount}][aturan_pakai]" placeholder="3x1 sesudah makan"
                       class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
        </div>
        <button type="button" onclick="hapusResep(${resepCount})"
                class="mt-5 p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded transition flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>`;

    document.getElementById('resep-container').insertAdjacentHTML('beforeend', html);
}

function hapusResep(id) {
    document.getElementById(`resep-${id}`).remove();
    if (document.querySelectorAll('.resep-item').length === 0) {
        document.getElementById('resep-empty').classList.remove('hidden');
    }
}

document.getElementById('btn-tambah-resep').addEventListener('click', tambahResep);
</script>
@endpush

@endsection