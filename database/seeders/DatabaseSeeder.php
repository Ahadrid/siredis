<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ✅ 1. Jalankan RolePermissionSeeder DULU sebelum assignRole
        $this->call([
            RolePermissionSeeder::class,
            ObatSeeder::class,
        ]);

        // ✅ 2. Gunakan config() bukan env() langsung
        $name  = config('app.admin_name', 'Super Admin');
        $email = config('app.admin_email');
        $pass  = config('app.admin_password');

        if ($email && $pass) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => $name,
                    'password'          => Hash::make($pass),
                    'email_verified_at' => now(),
                ]
            );

            // ✅ 3. syncRoles lebih aman dari assignRole (hindari duplikasi role)
            $user->syncRoles('superadmin');

            $this->command->info("✅ Akun Superadmin ({$email}) berhasil dibuat.");
        } else {
            $this->command->error('❌ Gagal: ADMIN_EMAIL atau ADMIN_PASSWORD belum diset di .env');
        }
    }
}