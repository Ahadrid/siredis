<?php

namespace App\Http\Controllers;

use App\Models\{RekamMedis, Kunjungan, Obat};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', RekamMedis::class);

        $rekamMedis = RekamMedis::with('pasien', 'dokter', 'kunjungan')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('pasien', fn($p) =>
                    $p->where('nama', 'like', "%{$request->search}%")
                      ->orWhere('no_rm', 'like', "%{$request->search}%")
                )->orWhere('diagnosis', 'like', "%{$request->search}%");
            })
            ->when($request->dokter_id, fn($q) =>
                $q->where('dokter_id', $request->dokter_id)
            )
            ->when($request->dari, fn($q) =>
                $q->whereDate('created_at', '>=', $request->dari)
            )
            ->when($request->sampai, fn($q) =>
                $q->whereDate('created_at', '<=', $request->sampai)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $dokters = \App\Models\User::role('dokter')->orderBy('name')->get();

        return view('rekam-medis.index', compact('rekamMedis', 'dokters'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', RekamMedis::class);

        $kunjungan = Kunjungan::with('pasien')->findOrFail($request->kunjungan_id);
        $obats     = Obat::where('stok', '>', 0)->orderBy('nama_obat')->get();

        // Return JSON untuk modal
        return response()->json([
            'kunjungan' => $kunjungan->load('pasien'),
            'obats'     => $obats,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', RekamMedis::class);

        $validated = $request->validate([
            'kunjungan_id'      => 'required|exists:kunjungans,id',
            'anamnesis'         => 'required|string',
            'pemeriksaan_fisik' => 'nullable|string',
            'tekanan_darah'     => 'nullable|string|max:20',
            'suhu'              => 'nullable|numeric|between:30,45',
            'nadi'              => 'nullable|integer|between:30,250',
            'respirasi'         => 'nullable|integer|between:5,60',
            'berat_badan'       => 'nullable|numeric|between:1,300',
            'diagnosis'         => 'required|string',
            'kode_icd'          => 'nullable|string|max:10',
            'tindakan'          => 'nullable|string',
            'catatan'           => 'nullable|string',
            'resep'             => 'nullable|array',
            'resep.*.obat_id'      => 'required|exists:obats,id',
            'resep.*.jumlah'       => 'required|integer|min:1',
            'resep.*.aturan_pakai' => 'required|string|max:100',
        ]);

        $kunjungan = Kunjungan::findOrFail($validated['kunjungan_id']);

        $rekamMedis = RekamMedis::create([
            ...$validated,
            'pasien_id' => $kunjungan->pasien_id,
            'dokter_id' => Auth::id(),
        ]);

        if (!empty($validated['resep'])) {
            foreach ($validated['resep'] as $item) {
                $rekamMedis->reseps()->create($item);
                Obat::find($item['obat_id'])->decrement('stok', $item['jumlah']);
            }
        }

        $kunjungan->update(['status' => 'selesai']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rekam medis berhasil disimpan.',
            ]);
        }

        return redirect()->route('rekam-medis.index')->with('success', 'Rekam medis berhasil disimpan.');
    }

    public function show(RekamMedis $rekamMedis)
    {
        $this->authorize('view', $rekamMedis);

        $rekamMedis->load('pasien', 'dokter', 'reseps.obat', 'kunjungan');

        // Return JSON untuk modal
        if (request()->expectsJson()) {
            return response()->json($rekamMedis);
        }

        return view('rekam-medis.index');
    }

    public function edit(RekamMedis $rekamMedis)
    {
        $this->authorize('update', $rekamMedis);

        $rekamMedis->load('reseps.obat', 'kunjungan.pasien');
        $obats = Obat::orderBy('nama_obat')->get();

        return response()->json([
            'rekamMedis' => $rekamMedis,
            'obats'      => $obats,
        ]);
    }

    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $this->authorize('update', $rekamMedis);

        $validated = $request->validate([
            'anamnesis'         => 'required|string',
            'pemeriksaan_fisik' => 'nullable|string',
            'tekanan_darah'     => 'nullable|string|max:20',
            'suhu'              => 'nullable|numeric|between:30,45',
            'nadi'              => 'nullable|integer|between:30,250',
            'respirasi'         => 'nullable|integer|between:5,60',
            'berat_badan'       => 'nullable|numeric|between:1,300',
            'diagnosis'         => 'required|string',
            'kode_icd'          => 'nullable|string|max:10',
            'tindakan'          => 'nullable|string',
            'catatan'           => 'nullable|string',
        ]);

        $rekamMedis->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rekam medis berhasil diperbarui.',
            ]);
        }

        return redirect()->route('rekam-medis.index')->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function destroy(RekamMedis $rekamMedis)
    {
        // Rekam medis tidak boleh dihapus — tolak semua request
        abort(403, 'Rekam medis tidak dapat dihapus.');
    }
}