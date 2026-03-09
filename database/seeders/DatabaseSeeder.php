<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $name = env('ADMIN_NAME', 'Default Admin');
        $username = env('ADMIN_USERNAME');
        $email = env('ADMIN_EMAIL');
        $pass = env('ADMIN_PASSWORD');

        if ($email && $pass) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'username' => $username,
                    'password' => Hash::make($pass),
                    // 'role' => 'admin',
                    'email_verified_at' => now(),
                ]
            );
            $this->command->info("Akun Admin ($email) berhasil dibuat");
        }
        else{
            $this->command->error("Gagal Seeding");
        }

        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
