<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'lihat pasien',
            'tambah pasien',
            'edit pasien',
            'hapus pasien',
            'lihat rekam medis',
            'tambah rekam medis'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superadmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);
        $dokter = Role::create(['name' => 'dokter']);
        $perawat = Role::create(['name' => 'perawat']);
        $resepsionis = Role::create(['name' => 'resepsionis']);

        $superadmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(Permission::all());

        $dokter->givePermissionTo([
            'lihat pasien',
            'lihat rekam medis',
            'tambah rekam medis'
        ]);

        $perawat->givePermissionTo([
            'lihat pasien'
        ]);

        $resepsionis->givePermissionTo([
            'lihat pasien',
            'tambah pasien',
            'edit pasien',
            'lihat rekam medis',
        ]);
    }
}
