<?php

namespace App\Http\Controllers;

use App\Models\{Kunjungan, Pasien, User};
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $kunjungans = Kunjungan::with('pasien', 'dokter')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->tanggal, fn($q) => $q->whereDate('tanggal_kunjungan', $request->tanggal))
            ->latest()->paginate(15);

        return view('kunjungan.index', compact('kunjungans'));
    }

    // public function create()
    // {
    //     $kunjungans = Kunjungan::all();
    //     $pasiens  = Pasien::orderBy('nama')->get();
    //     $dokters  = User::role('dokter')->orderBy('name')->get();
    //     return view('kunjungan.create', compact('pasiens', 'dokters'));
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pasien_id'          => 'required|exists:pasien,id',
            'dokter_id'          => 'required|exists:users,id',
            'tanggal_kunjungan'  => 'required|date',
            'keluhan'            => 'required|string',
        ]);

        $validated['no_kunjungan'] = Kunjungan::generateNoKunjungan();
        Kunjungan::create($validated);

        return redirect()->route('kunjungan.index')
                         ->with('success', 'Kunjungan berhasil dibuat.');
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load('pasien', 'dokter', 'rekamMedis.reseps.obat');
        return response()->json($kunjungan);
    }

    public function updateStatus(Request $request, Kunjungan $kunjungan)
    {
        $request->validate(['status' => 'required|in:menunggu,dalam_proses,selesai,batal']);
        $kunjungan->update(['status' => $request->status]);

        return back()->with('success', 'Status kunjungan diperbarui.');
    }
}