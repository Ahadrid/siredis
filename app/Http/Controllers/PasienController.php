<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $pasiens = Pasien::when($request->search, function ($q) use ($request) {
                        $q->where('nama', 'like', "%{$request->search}%")
                          ->orWhere('no_rm', 'like', "%{$request->search}%")
                          ->orWhere('nik', 'like', "%{$request->search}%");
                    })
                    ->latest()->paginate(15);

        return view('pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date',
            'nik'            => 'nullable|digits:16|unique:pasiens',
            'no_hp'          => 'nullable|string|max:15',
            'alamat'         => 'nullable|string',
            'golongan_darah' => 'required|in:A,B,AB,O,?',
            'riwayat_alergi' => 'nullable|string',
        ]);

        $validated['no_rm'] = Pasien::generateNoRM();
        Pasien::create($validated);

        return redirect()->route('pasien.index')
                         ->with('success', 'Pasien berhasil didaftarkan.');
    }

    public function show(Pasien $pasien)
    {
        $pasien->load(['kunjungans.rekamMedis', 'rekamMedis.dokter']);
        return view('pasien.show', compact('pasien'));
    }

    public function edit(Pasien $pasien)
    {
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date',
            'nik'            => 'nullable|digits:16|unique:pasiens,nik,' . $pasien->id,
            'no_hp'          => 'nullable|string|max:15',
            'alamat'         => 'nullable|string',
            'golongan_darah' => 'required|in:A,B,AB,O,?',
            'riwayat_alergi' => 'nullable|string',
        ]);

        $pasien->update($validated);

        return redirect()->route('pasien.index')
                         ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien)
    {
        $pasien->delete();
        return redirect()->route('pasien.index')
                         ->with('success', 'Pasien berhasil dihapus.');
    }
}