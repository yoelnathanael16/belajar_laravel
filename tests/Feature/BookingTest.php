<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    // Semua test class memakai DatabaseSeeder yang sama: SQLite di memori hanya di-seed sekali per run
    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'jenis_acara' => 'Ulang Tahun',
            'jumlah_tamu' => '35-50 orang',
            'tanggal' => now()->addWeek()->toDateString(),
            'jam' => '12:00',
            'items' => [
                ['id' => 'nasi-putih', 'qty' => 40],
                ['id' => 'garang-asem', 'qty' => 10],
            ],
        ], $override);
    }

    public function test_guest_cannot_book(): void
    {
        $this->postJson('/booking', $this->payload())->assertUnauthorized();
    }

    public function test_booking_uses_database_prices_not_browser_prices(): void
    {
        $user = User::factory()->create();
        $nasi = Menu::firstWhere('slug', 'nasi-putih');
        $garang = Menu::firstWhere('slug', 'garang-asem');

        // Browser mencoba mengirim harga dan total palsu
        $response = $this->actingAs($user)->postJson('/booking', $this->payload([
            'total' => 1,
            'items' => [
                ['id' => 'nasi-putih', 'qty' => 40, 'price' => 1],
                ['id' => 'garang-asem', 'qty' => 10, 'price' => 1],
            ],
        ]))->assertCreated();

        $booking = $user->bookings()->with('items')->sole();
        $expected = 40 * $nasi->harga + 10 * $garang->harga;

        $response->assertExactJson(['kode' => $booking->kode, 'total' => $expected]);
        $this->assertSame($expected, $booking->total_harga);
        $this->assertSame('menunggu', $booking->status);
        $this->assertSame([$nasi->harga, $garang->harga], $booking->items->pluck('harga_satuan')->all());
    }

    public function test_booking_rejects_unavailable_menu_past_date_and_unknown_options(): void
    {
        $user = User::factory()->create();
        Menu::where('slug', 'garang-asem')->update(['tersedia' => false]);

        $this->actingAs($user)->postJson('/booking', $this->payload([
            'jenis_acara' => 'Pernikahan',
            'jumlah_tamu' => '1000 orang',
            'tanggal' => now()->subDay()->toDateString(),
            'jam' => '22:00',
        ]))->assertUnprocessable()->assertJsonValidationErrors([
            'jenis_acara', 'jumlah_tamu', 'tanggal', 'jam', 'items.1.id',
        ]);

        $this->actingAs($user)->postJson('/booking', $this->payload(['items' => []]))
            ->assertUnprocessable()->assertJsonValidationErrors('items');

        $this->assertSame(0, $user->bookings()->count());
    }

    public function test_profile_shows_only_own_bookings(): void
    {
        $user = User::factory()->create();
        $mine = Booking::factory()->for($user)->withItems(2)->create(['status' => 'ditolak', 'alasan_penolakan' => 'Meja penuh.']);
        $other = Booking::factory()->withItems(2)->create();

        $this->actingAs($user)->get('/profil')
            ->assertOk()
            ->assertSee($mine->kode)
            ->assertSee('Meja penuh.')
            ->assertSee('Ditolak')
            ->assertDontSee('"code":"' . $other->kode . '"', false);
    }
}
