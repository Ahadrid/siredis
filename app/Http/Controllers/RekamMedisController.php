<?php

namespace App\Http\Controllers;

use App\Models\{RekamMedis, Kunjungan, Obat};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    use AuthorizesRequests;
    public function create(Request $request)
    {
        $this->authorize('tambah rekam medis'); // via Gate/Permission
        $kunjungan = Kunjungan::with('pasien')->findOrFail($request->kunjungan_id);
        $obats     = Obat::where('stok', '>', 0)->orderBy('nama_obat')->get();
        return view('rekam-medis.create', compact('kunjungan', 'obat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kunjungan_id'     => 'required|exists:kunjungans,id',
            'anamnesis'        => 'required|string',
            'pemeriksaan_fisik'=> 'nullable|string',
            'tekanan_darah'    => 'nullable|string',
            'suhu'             => 'nullable|numeric',
            'nadi'             => 'nullable|integer',
            'respirasi'        => 'nullable|integer',
            'berat_badan'      => 'nullable|numeric',
            'diagnosis'        => 'required|string',
            'kode_icd'         => 'nullable|string',
            'tindakan'         => 'nullable|string',
            'catatan'          => 'nullable|string',
            // Resep (array)
            'resep.*.obat_id'     => 'required|exists:obat,id',
            'resep.*.jumlah'      => 'required|integer|min:1',
            'resep.*.aturan_pakai'=> 'required|string',
        ]);

        $kunjungan = Kunjungan::findOrFail($validated['kunjungan_id']);

        $rekamMedis = RekamMedis::create([
            ...$validated,
            'pasien_id' => $kunjungan->pasien_id,
            'dokter_id' => auth()->id(),
        ]);

        // Simpan resep & kurangi stok
        if (!empty($validated['resep'])) {
            foreach ($validated['resep'] as $item) {
                $rekamMedis->reseps()->create($item);
                $obat = Obat::find($item['obat_id']);
                $obat->decrement('stok', $item['jumlah']);
            }
        }

        // Update status kunjungan
        $kunjungan->update(['status' => 'selesai']);

        return redirect()->route('kunjungan.show', $kunjungan)
                         ->with('success', 'Rekam medis berhasil disimpan.');
    }

    public function show(RekamMedis $rekamMedis)
    {
        $rekamMedis->load('pasien', 'dokter', 'reseps.obat', 'kunjungan');
        return view('rekam-medis.show', compact('rekamMedis'));
    }
}