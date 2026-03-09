<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Pasien
            'lihat pasien', 'tambah pasien', 'edit pasien', 'hapus pasien',
            // Kunjungan
            'lihat kunjungan', 'tambah kunjungan', 'edit kunjungan',
            // Rekam Medis
            'lihat rekam medis', 'tambah rekam medis', 'edit rekam medis',
            // Obat
            'lihat obat', 'kelola obat',
            // User & Sistem
            'kelola user', 'kelola role', 'lihat log aktivitas',
            // Laporan
            'lihat laporan', 'export laporan',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ✅ Superadmin — semua akses tanpa terkecuali
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $superadmin->syncPermissions(Permission::all());

        // ✅ Admin — kelola operasional klinik, TIDAK bisa:
        //    - tambah/edit rekam medis (itu wewenang medis/dokter)
        //    - kelola role (hanya superadmin)
        //    - lihat log aktivitas sistem
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'lihat pasien', 'tambah pasien', 'edit pasien', 'hapus pasien',
            'lihat kunjungan', 'tambah kunjungan', 'edit kunjungan',
            'lihat rekam medis',                // ❌ tidak bisa tambah/edit rekam medis
            'lihat obat', 'kelola obat',
            'kelola user',                      // ❌ tidak bisa kelola role
            'lihat laporan', 'export laporan',
        ]);

        $dokter = Role::firstOrCreate(['name' => 'dokter']);
        $dokter->syncPermissions([
            'lihat pasien', 'tambah pasien', 'edit pasien',
            'lihat kunjungan', 'tambah kunjungan', 'edit kunjungan',
            'lihat rekam medis', 'tambah rekam medis', 'edit rekam medis',
            'lihat obat', 'lihat laporan',
        ]);

        $perawat = Role::firstOrCreate(['name' => 'perawat']);
        $perawat->syncPermissions([
            'lihat pasien', 'tambah pasien',
            'lihat kunjungan', 'tambah kunjungan',
            'lihat rekam medis',
            'lihat obat',
        ]);

        // Buat user admin default
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@klinik.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password')]
        );
        $userAdmin->assignRole('admin');

        // Buat user dokter contoh
        $userDokter = User::firstOrCreate(
            ['email' => 'dokter@klinik.com'],
            ['name' => 'dr. Budi Santoso', 'password' => Hash::make('password')]
        );
        $userDokter->assignRole('dokter');
    }
}