<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'jenis_acara' => fake()->randomElement(Booking::JENIS_ACARA),
            'jumlah_tamu' => fake()->randomElement(Booking::JUMLAH_TAMU),
            'tanggal' => fake()->dateTimeBetween('-1 month', '+2 months')->format('Y-m-d'),
            'jam' => fake()->randomElement(Booking::JAM),
            'total_harga' => 0, // dihitung ulang lewat state withItems()
            'status' => fake()->randomElement(['menunggu', 'dikonfirmasi', 'ditolak', 'selesai']),
            'alasan_penolakan' => fn (array $attrs) => $attrs['status'] === 'ditolak'
                ? fake()->randomElement([
                    'Meja belum tersedia pada tanggal dan jam tersebut.',
                    'Jumlah tamu melebihi kapasitas kami.',
                ])
                : null,
        ];
    }

    public function menunggu(): static
    {
        return $this->state(fn () => ['status' => 'menunggu']);
    }

    public function dikonfirmasi(): static
    {
        return $this->state(fn () => ['status' => 'dikonfirmasi']);
    }

    public function ditolak(?string $alasan = null): static
    {
        return $this->state(fn () => [
            'status' => 'ditolak',
            'alasan_penolakan' => $alasan ?? 'Tanggal sudah penuh.',
        ]);
    }

    /**
     * Buat booking beserta item menunya, lalu hitung total_harga dari subtotal item.
     */
    public function withItems(int $count = 3): static
    {
        return $this->afterCreating(function (Booking $booking) use ($count) {
            $menus = Menu::where('tersedia', true)->inRandomOrder()->limit($count)->get();

            if ($menus->count() < $count) {
                $menus = $menus->concat(
                    Menu::factory()->tersedia()->count($count - $menus->count())->create()
                );
            }

            foreach ($menus as $menu) {
                BookingItem::factory()->for($booking)->for($menu)->create();
            }

            $booking->update(['total_harga' => $booking->items()->sum('subtotal')]);
        });
    }
}
