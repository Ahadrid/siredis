<?php

namespace App\Policies;

use App\Models\RekamMedis;
use App\Models\User;

class RekamMedisPolicy
{
    // Superadmin bypass semua — ditangani Gate::before di AppServiceProvider

    // Lihat daftar rekam medis
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('lihat rekam medis');
    }

    // Lihat detail rekam medis
    public function view(User $user, RekamMedis $rekamMedis): bool
    {
        if (!$user->hasPermissionTo('lihat rekam medis')) {
            return false;
        }

        // Dokter hanya bisa lihat miliknya sendiri, admin & perawat bisa semua
        if ($user->hasRole('dokter')) {
            return $rekamMedis->dokter_id === $user->id;
        }

        return true;
    }

    // Tambah rekam medis — hanya dokter
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('tambah rekam medis');
    }

    // Edit rekam medis — hanya dokter pemilik
    public function update(User $user, RekamMedis $rekamMedis): bool
    {
        if (!$user->hasPermissionTo('edit rekam medis')) {
            return false;
        }

        // Dokter hanya bisa edit rekam medis miliknya sendiri
        if ($user->hasRole('dokter')) {
            return $rekamMedis->dokter_id === $user->id;
        }

        return true;
    }

    // Hapus rekam medis — tidak ada yang boleh hapus (data medis legal)
    public function delete(User $user, RekamMedis $rekamMedis): bool
    {
        return false;
    }
}