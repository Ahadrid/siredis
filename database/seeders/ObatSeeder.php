<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sekarang = Carbon::now();

        $dataObat = [
            [
                'kode_obat' => 'OBT-001',
                'nama_obat' => 'Paracetamol 500mg',
                'kategori'  => 'Analgesik / Antipiretik',
                'deskripsi' => 'Obat penurun panas dan pereda nyeri ringan hingga sedang.',
                'satuan'    => 'Strip',
                'stok'      => 150,
                'harga'     => 5000.00,
                'created_at'=> $sekarang,
                'updated_at'=> $sekarang,
            ],
            [
                'kode_obat' => 'OBT-002',
                'nama_obat' => 'Amoxicillin 500mg',
                'kategori'  => 'Antibiotik',
                'deskripsi' => 'Antibiotik penisilin untuk mengatasi infeksi bakteri.',
                'satuan'    => 'Strip',
                'stok'      => 75,
                'harga'     => 12000.00,
                'created_at'=> $sekarang,
                'updated_at'=> $sekarang,
            ],
            [
                'kode_obat' => 'OBT-003',
                'nama_obat' => 'Omeprazole 20mg',
                'kategori'  => 'Obat Lambung',
                'deskripsi' => 'Obat untuk menurunkan produksi asam lambung berlebih (GERD/maag).',
                'satuan'    => 'Kapsul',
                'stok'      => 100,
                'harga'     => 25000.00,
                'created_at'=> $sekarang,
                'updated_at'=> $sekarang,
            ],
            [
                'kode_obat' => 'OBT-004',
                'nama_obat' => 'Sirup OBH Combi 100ml',
                'kategori'  => 'Obat Batuk',
                'deskripsi' => 'Obat batuk hitam untuk meredakan batuk berdahak dan pilek.',
                'satuan'    => 'Botol',
                'stok'      => 50,
                'harga'     => 18500.00,
                'created_at'=> $sekarang,
                'updated_at'=> $sekarang,
            ],
            [
                'kode_obat' => 'OBT-005',
                'nama_obat' => 'Betadine Antiseptic 15ml',
                'kategori'  => 'Antiseptik',
                'deskripsi' => 'Cairan antiseptik untuk membersihkan dan mencegah infeksi pada luka.',
                'satuan'    => 'Botol',
                'stok'      => 30,
                'harga'     => 15000.00,
                'created_at'=> $sekarang,
                'updated_at'=> $sekarang,
            ],
        ];

        // Memasukkan data ke tabel 'obat' sesuai schema kamu
        DB::table('obat')->insert($dataObat);
    }
}