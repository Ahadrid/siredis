<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $pasiens = Pasien::when($request->search, function ($q) use ($request) {
                        $q->where('nama', 'ilike', "%{$request->search}%")
                          ->orWhere('no_rm', 'ilike', "%{$request->search}%")
                          ->orWhere('nik', 'ilike', "%{$request->search}%");
                    })
                    ->latest()->paginate(5);

        return view('pasien.index', compact('pasiens'));
    }

    // public function create()
    // {
    //     return view('pasien.create');
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date',
            'nik'            => 'nullable|digits:16|unique:pasien',
            'no_hp'          => 'nullable|string|max:15',
            'alamat'         => 'nullable|string',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
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
        return response()->json($pasien);
    }

    public function edit(Pasien $pasien)
    {
        return response()->json($pasien);
    }

    public function update(Request $request, Pasien $pasien)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date',
            'nik'            => 'nullable|digits:16|unique:pasien,nik,' . $pasien->id,
            'no_hp'          => 'nullable|string|max:15',
            'alamat'         => 'nullable|string',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
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