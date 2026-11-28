<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Menu;
use App\Models\User;
use App\Support\MenuCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    // Semua test class memakai DatabaseSeeder yang sama: SQLite di memori hanya di-seed sekali per run
    protected $seed = true;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->admin = User::factory()->admin()->create(['password' => 'rahasia123']);
    }

    public function test_only_admin_can_open_admin_pages(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get('/admin/pesanan')->assertForbidden();

        foreach (['/admin', '/admin/pesanan', '/admin/menu'] as $url) {
            $this->actingAs($this->admin)->get($url)->assertOk();
        }
    }

    public function test_admin_login_goes_to_admin_panel(): void
    {
        $this->post('/masuk', ['email' => $this->admin->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_accept_reject_and_finish_follow_allowed_steps(): void
    {
        $a = Booking::factory()->withItems(1)->create(['status' => 'menunggu']);
        $b = Booking::factory()->withItems(1)->create(['status' => 'menunggu']);

        $this->actingAs($this->admin);

        // Terima, lalu tandai selesai
        $this->patch("/admin/pesanan/{$a->id}", ['aksi' => 'terima'])->assertSessionHasNoErrors();
        $this->assertSame('dikonfirmasi', $a->fresh()->status);
        $this->patch("/admin/pesanan/{$a->id}", ['aksi' => 'selesai'])->assertSessionHasNoErrors();
        $this->assertSame('selesai', $a->fresh()->status);

        // Tolak wajib dengan alasan
        $this->patch("/admin/pesanan/{$b->id}", ['aksi' => 'tolak'])->assertSessionHasErrors('alasan_penolakan');
        $this->patch("/admin/pesanan/{$b->id}", ['aksi' => 'tolak', 'alasan_penolakan' => 'Meja penuh.'])->assertSessionHasNoErrors();
        $this->assertSame(['ditolak', 'Meja penuh.'], [$b->fresh()->status, $b->fresh()->alasan_penolakan]);

        // Pesanan yang sudah ditolak tidak bisa diterima lagi (mis. dua admin klik bersamaan)
        $this->patch("/admin/pesanan/{$b->id}", ['aksi' => 'terima'])->assertSessionHasErrors('aksi');
        $this->assertSame('ditolak', $b->fresh()->status);
    }

    public function test_search_by_code_finds_booking(): void
    {
        $booking = Booking::factory()->withItems(1)->create();

        $this->actingAs($this->admin)->get('/admin/pesanan?q=' . $booking->kode)
            ->assertOk()
            ->assertSee($booking->kode);
    }

    public function test_menu_changes_show_on_customer_site(): void
    {
        Storage::fake('public');
        $nasi = Menu::firstWhere('slug', 'nasi-putih');
        $botok = Menu::firstWhere('slug', 'botok-ontong');

        $this->actingAs($this->admin)->put('/admin/menu', [
            'menus' => [
                $nasi->id => ['harga' => 7000, 'keterangan' => 'Nasi <b>pulen</b>', 'tersedia' => '1'],
                $botok->id => ['harga' => $botok->harga, 'keterangan' => $botok->keterangan, 'tersedia' => '0'],
            ],
            // create() tidak butuh ekstensi GD seperti image()
            'foto' => [$nasi->id => UploadedFile::fake()->create('nasi.jpg', 120, 'image/jpeg')],
        ])->assertSessionHasNoErrors();

        $items = collect(app(MenuCatalog::class)->all()['items'])->keyBy('id');
        $this->assertSame(7000, $items['nasi-putih']['price']);
        $this->assertStringContainsString('/storage/menu/', $items['nasi-putih']['img']);
        $this->assertFalse($items->has('botok-ontong'));
        Storage::disk('public')->assertExists($nasi->fresh()->foto);
    }

    public function test_add_menu_only_with_existing_category(): void
    {
        $this->actingAs($this->admin);

        $this->post('/admin/menu', ['nama_menu' => 'Sayur Lodeh', 'kategori' => 'Kategori Baru', 'harga' => 10000])
            ->assertSessionHasErrors('kategori');

        $this->post('/admin/menu', [
            'nama_menu' => 'Sayur Lodeh', 'kategori' => 'Menu 12k|Sayur Kuah', 'harga' => 10000, 'tersedia' => '1',
        ])->assertSessionHasNoErrors();

        $item = collect(app(MenuCatalog::class)->all()['items'])->firstWhere('name', 'Sayur Lodeh');
        $this->assertSame(['sayur-lodeh', 'Menu 12k|Sayur Kuah', 10000], [$item['id'], $item['cat'], $item['price']]);
    }
}
