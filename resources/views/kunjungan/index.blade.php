@extends('layouts.app')
@section('title', 'Kunjungan')
@section('subtitle', 'Daftar antrian dan kunjungan pasien')

@section('content')

{{-- Filter & Actions --}}
<div
x-data="{
    openCreate:false,
    openShow:false,
    openStatus:false,
    openRM:false,
    kunjungan:{},
    rm:{}
}"
@open-rm.window="
    rm = { kunjungan_id: $event.detail.kunjungan.id }
    openRM = true
"
>

    <div class="flex items-center justify-between mb-5 gap-3 flex-wrap">
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <input type="date" name="tanggal" value="{{ request('tanggal', today()->format('Y-m-d')) }}"
                class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white mono">
            <select name="status" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                <option value="">Semua Status</option>
                <option value="menunggu"     {{ request('status') == 'menunggu'     ? 'selected' : '' }}>Menunggu</option>
                <option value="dalam_proses" {{ request('status') == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                <option value="selesai"      {{ request('status') == 'selesai'      ? 'selected' : '' }}>Selesai</option>
                <option value="batal"        {{ request('status') == 'batal'        ? 'selected' : '' }}>Batal</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">Filter</button>
            <a href="{{ route('kunjungan.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-700 text-sm">Reset</a>
        </form>

        @can('tambah kunjungan')
            <button
                @click="openCreate = true"
                class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Kunjungan
            </button>
        @endcan
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">No. Kunjungan</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Pasien</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Dokter</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Keluhan</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Waktu</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kunjungans as $k)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-5 py-3.5">
                            <span class="mono text-xs text-slate-600">{{ $k->no_kunjungan }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-slate-800">{{ $k->pasien->nama }}</div>
                            <div class="text-xs text-slate-400 mono">{{ $k->pasien->no_rm }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-slate-600 text-sm">{{ $k->dokter->name }}</td>
                        <td class="px-5 py-3.5 text-slate-600 max-w-xs">
                            <p class="truncate text-sm">{{ $k->keluhan }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-slate-400 text-xs mono">{{ $k->tanggal_kunjungan->format('H:i') }}</td>
                        <td class="px-5 py-3.5">
                            @php
                                $statusClass = [
                                    'menunggu'     => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'dalam_proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'selesai'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'batal'        => 'bg-red-50 text-red-600 border-red-200',
                                ][$k->status] ?? '';
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $k->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1 justify-end">
                                <button
                                    @click="
                                    fetch('/kunjungan/{{ $k->id }}')
                                    .then(res => res.json())
                                    .then(data => {
                                        kunjungan = data
                                        openShow = true
                                    })
                                    "
                                    class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded transition"
                                    title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>

                                @can('create', App\Models\RekamMedis::class)
                                    @if($k->status === 'dalam_proses')
                                        <button
                                            @click="bukaModalRM({{ $k->id }})"
                                            class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition"
                                            title="Rekam Medis">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </button>
                                    @endif
                                @endcan
                                {{-- Kunjungan Update Status --}}
                                @can('edit kunjungan')
                                @if($k->status === 'menunggu')
                                <form method="POST" action="{{ route('kunjungan.status', $k) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="dalam_proses">
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition" title="Proses">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm font-medium">Tidak ada kunjungan</p>
                                <p class="text-xs">Coba ubah filter tanggal atau status</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination :paginator="$kunjungans" label="kunjungan" />
    </div>
    @include('kunjungan.partials.create-modal')
    @include('kunjungan.partials.show-modal')
    @include('kunjungan.partials.status-modal')
    @include('kunjungan.partials.rekam-medis-modal')
</div>
<script>
function bukaModalRM(id)
{
    fetch(`/rekam-medis/create?kunjungan_id=${id}`)
    .then(res => res.json())
    .then(data => {

        window.dispatchEvent(new CustomEvent('open-rm', {
            detail: data
        }))

    })
}

document.addEventListener("submit", async function(e) {
    if (e.target.id !== "formRM") return
    e.preventDefault()

    const formData = new FormData(e.target)
    
    // Pastikan kunjungan_id terisi dari Alpine
    const kunjunganId = document.querySelector('#formRM [name="kunjungan_id"]').value
    console.log('kunjungan_id:', kunjunganId) // debug
    
    const res = await fetch('/rekam-medis', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })

    const data = await res.json()
    console.log('response:', data) // debug

    if (data.success) {
        location.reload()
    } else {
        console.error('Errors:', data.errors)
        alert('Gagal menyimpan: ' + JSON.stringify(data.errors))
    }
})
</script>
@endsection