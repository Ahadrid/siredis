@extends('layouts.app')
@section('title', 'Data Pasien')
@section('subtitle', 'Manajemen data pasien klinik')

@section('content')

<div x-data="{ 
        openCreate:false, 
        openShow:false, 
        openEdit:false, 
        pasien:{}
    }">
    {{-- Header Actions --}}
    <div class="flex items-center justify-between mb-5">
        <form method="GET" class="flex items-center gap-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, No. RM, NIK..."
                    class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent w-72 bg-white">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">Cari</button>
            @if(request('search'))
                <a href="{{ route('pasien.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-700 text-sm transition">Reset</a>
            @endif
        </form>

        @can('tambah pasien')
            <button
                @click="openCreate = true"
                class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Pasien Baru
            </button>    
        @endcan
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">No. RM</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama Pasien</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Usia / JK</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">No. HP</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Gol. Darah</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Terdaftar</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pasiens as $pasien)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-5 py-3.5">
                            <span class="mono text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded font-medium">{{ $pasien->no_rm }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-slate-800">{{ $pasien->nama }}</div>
                            @if($pasien->nik)
                            <div class="text-xs text-slate-400 mono mt-0.5">NIK: {{ $pasien->nik }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-slate-600">
                            {{ $pasien->umur }} th
                            <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $pasien->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                {{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 mono text-xs">{{ $pasien->no_hp ?? '-' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-100">
                                {{ $pasien->golongan_darah }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-400 text-xs mono">{{ $pasien->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1 justify-end">
                                {{-- SHOW --}}
                                <button
                                    @click="
                                        pasien = {
                                            id: @js($pasien->id),
                                            no_rm: @js($pasien->no_rm),
                                            nama: @js($pasien->nama),
                                            jenis_kelamin: @js($pasien->jenis_kelamin),
                                            tanggal_lahir: @js($pasien->tanggal_lahir_indo),
                                            nik: @js($pasien->nik),
                                            no_hp: @js($pasien->no_hp),
                                            alamat: @js($pasien->alamat),
                                            golongan_darah: @js($pasien->golongan_darah),
                                            riwayat_alergi: @js($pasien->riwayat_alergi)
                                        };
                                        openShow = true
                                    "
                                    class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded transition"
                                    title="Detail">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                {{-- EDIT --}}
                                @can('edit pasien')
                                <button
                                    @click="
                                        pasien = {
                                            id: @js($pasien->id),
                                            no_rm: @js($pasien->no_rm),
                                            nama: @js($pasien->nama),
                                            jenis_kelamin: @js($pasien->jenis_kelamin),
                                            tanggal_lahir: @js($pasien->tanggal_lahir->format('Y-m-d')),
                                            nik: @js($pasien->nik),
                                            no_hp: @js($pasien->no_hp),
                                            alamat: @js($pasien->alamat),
                                            golongan_darah: @js($pasien->golongan_darah),
                                            riwayat_alergi: @js($pasien->riwayat_alergi)
                                        };
                                        openEdit = true
                                    "
                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition"
                                        title="Edit">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                        a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                @endcan
                                @can('hapus pasien')
                                <form method="POST" action="{{ route('pasien.destroy', $pasien) }}"
                                    onsubmit="return confirm('Hapus pasien {{ $pasien->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="text-sm font-medium">Belum ada data pasien</p>
                                <p class="text-xs">Mulai daftarkan pasien baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination :paginator="$pasiens" label="pasien" />
    </div>  
    @include('pasien.partials.create-modal')                                            
    @include('pasien.partials.show-modal')                                            
    @include('pasien.partials.edit-modal')                                            
</div>
@endsection