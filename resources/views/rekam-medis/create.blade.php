@extends('layouts.app')
@section('title', 'Rekam Medis')
@section('content')
<div class="flex items-center justify-center py-20 text-center text-slate-400">
    <div>
        <p class="text-sm">Rekam medis dibuat melalui halaman Kunjungan.</p>
        <a href="{{ route('kunjungan.index') }}" class="text-teal-600 hover:underline text-sm mt-2 inline-block">
            → Ke Halaman Kunjungan
        </a>
    </div>
</div>
@endsection