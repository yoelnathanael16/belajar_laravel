<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_menu_matches_menu_json(): void
    {
        $this->assertSame(54, Menu::count());
        $this->assertSame(
            ['botok-ontong', 'garang-asem', 'mujair-bumbu', 'pepes-tuna'],
            Menu::where('favorit', true)->orderBy('slug')->pluck('slug')->all(),
        );

        $nasi = Menu::where('slug', 'nasi-putih')->first();
        $this->assertSame(['Menu 12k', 'Nasi'], [$nasi?->kategori, $nasi?->subkategori]);
    }

    public function test_bookings_use_form_options_and_correct_totals(): void
    {
        $this->assertTrue(User::where('role', 'admin')->exists());
        $this->assertGreaterThan(0, Booking::count());

        foreach (Booking::with('items')->get() as $booking) {
            $this->assertContains($booking->jenis_acara, Booking::JENIS_ACARA);
            $this->assertContains($booking->jumlah_tamu, Booking::JUMLAH_TAMU);
            $this->assertSame($booking->items->sum('subtotal'), $booking->total_harga);
            $this->assertMatchesRegularExpression('/^PRS-\d{4,}$/', $booking->kode);
        }
    }
}
