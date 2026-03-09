<?php

namespace App\Http\Controllers;

use App\Models\{Pasien, Kunjungan, RekamMedis, Obat};

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_pasien'       => Pasien::count(),
            'kunjungan_hari_ini' => Kunjungan::whereDate('tanggal_kunjungan', today())->count(),
            'kunjungan_menunggu' => Kunjungan::where('status', 'menunggu')->count(),
            'obat_stok_tipis'    => Obat::where('stok', '<', 10)->count(),
            'kunjungan_terbaru'  => Kunjungan::with('pasien', 'dokter')
                                        ->latest()->take(5)->get(),
        ];

        return view('dashboard', $data);
    }
}