<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role', 'customer')->each(function (User $user) {
            Booking::factory()
                ->count(fake()->numberBetween(1, 3))
                ->for($user, 'user')
                ->withItems(fake()->numberBetween(2, 5))
                ->create();
        });
    }
}
