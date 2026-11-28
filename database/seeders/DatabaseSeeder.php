<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting: users & menus dulu, baru bookings (beserta booking_items)
        $this->call([
            UserSeeder::class,
            MenuSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
