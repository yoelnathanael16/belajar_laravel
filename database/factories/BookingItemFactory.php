<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingItem>
 */
class BookingItemFactory extends Factory
{
    protected $model = BookingItem::class;

    public function definition(): array
    {
        // Urutan key penting: closure di bawah membaca atribut yang sudah di-resolve di atasnya.
        return [
            'booking_id' => Booking::factory(),
            'menu_id' => Menu::factory(),
            'qty' => fake()->numberBetween(5, 200),
            'harga_satuan' => fn (array $attrs) => Menu::find($attrs['menu_id'])->harga,
            'subtotal' => fn (array $attrs) => $attrs['qty'] * $attrs['harga_satuan'],
        ];
    }
}
