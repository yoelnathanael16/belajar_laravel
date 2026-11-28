<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin (password: "password")
        User::factory()->admin()->create([
            'nama' => 'Admin',
            'email' => 'admin@example.com',
            'no_whatsapp' => '081234567890',
        ]);

        // Akun customer untuk testing (password: "password")
        User::factory()->customer()->create([
            'nama' => 'Customer Demo',
            'email' => 'customer@example.com',
            'no_whatsapp' => '081298765432',
        ]);

        User::factory()->customer()->count(10)->create();
    }
}
