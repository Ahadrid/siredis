<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('lihat obat');

        $obats = Obat::when($request->search, function ($q) use ($request) {
                        $q->where('nama_obat', 'like', "%{$request->search}%")
                          ->orWhere('kode_obat', 'like', "%{$request->search}%");
                    })
                    ->when($request->stok === 'tipis', fn($q) => $q->where('stok', '<', 10))
                    ->latest()
                    ->paginate(15);

        return view('obat.index', compact('obats'));
    }

    public function create()
    {
        $this->authorize('kelola obat');

        return view('obat.create');
    }

    public function store(Request $request)
    {
        $this->authorize('kelola obat');

        $validated = $request->validate([
            'kode_obat'  => 'required|string|max:20|unique:obats',
            'nama_obat'  => 'required|string|max:100',
            'satuan'     => 'required|string|max:20',
            'stok'       => 'required|integer|min:0',
            'harga'      => 'required|numeric|min:0',
        ]);

        Obat::create($validated);

        return redirect()->route('obat.index')
                         ->with('success', "Obat {$validated['nama_obat']} berhasil ditambahkan.");
    }

    public function show(Obat $obat)
    {
        $this->authorize('lihat obat');

        // Riwayat pemakaian obat dari resep
        $riwayat = $obat->reseps()
                        ->with('rekamMedis.pasien', 'rekamMedis.dokter')
                        ->latest()
                        ->paginate(10);

        return view('obat.show', compact('obat', 'riwayat'));
    }

    public function edit(Obat $obat)
    {
        $this->authorize('kelola obat');

        return view('obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $this->authorize('kelola obat');

        $validated = $request->validate([
            'kode_obat'  => 'required|string|max:20|unique:obats,kode_obat,' . $obat->id,
            'nama_obat'  => 'required|string|max:100',
            'satuan'     => 'required|string|max:20',
            'stok'       => 'required|integer|min:0',
            'harga'      => 'required|numeric|min:0',
        ]);

        $obat->update($validated);

        return redirect()->route('obat.index')
                         ->with('success', "Data obat {$obat->nama_obat} berhasil diperbarui.");
    }

    public function destroy(Obat $obat)
    {
        $this->authorize('kelola obat');

        // Cek apakah obat pernah digunakan di resep
        if ($obat->reseps()->exists()) {
            return redirect()->route('obat.index')
                             ->with('error', "Obat {$obat->nama_obat} tidak bisa dihapus karena sudah pernah digunakan.");
        }

        $obat->delete();

        return redirect()->route('obat.index')
                         ->with('success', "Obat {$obat->nama_obat} berhasil dihapus.");
    }

    // Tambah stok obat (fitur terpisah)
    public function tambahStok(Request $request, Obat $obat)
    {
        $this->authorize('kelola obat');

        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat->increment('stok', $request->jumlah);

        return redirect()->route('obat.show', $obat)
                         ->with('success', "Stok {$obat->nama_obat} berhasil ditambah {$request->jumlah} {$obat->satuan}.");
    }
}