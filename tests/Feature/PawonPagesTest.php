<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Support\MenuCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PawonPagesTest extends TestCase
{
    use RefreshDatabase;

    // Halaman Beranda, Menu, Booking, dan Profil membaca menu dari database.
    // Semua test class memakai DatabaseSeeder yang sama: SQLite di memori hanya di-seed sekali per run.
    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        // Tidak perlu hasil build Vite untuk menguji rute dan view.
        $this->withoutVite();
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function pages(): array
    {
        return [
            'home' => ['home', '/'],
            'about' => ['about', '/tentang-kami'],
            'menu' => ['menu', '/menu'],
            'booking' => ['booking', '/booking'],
            'contact' => ['contact', '/hubungi-kami'],
            'login' => ['login', '/masuk'],
            'register' => ['register', '/daftar'],
            // /profil butuh login, diuji di AuthTest
        ];
    }

    #[DataProvider('pages')]
    public function test_page_renders(string $name, string $uri): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertSee('Pawon Ratu Roso')
            ->assertSee(route($name), false);
    }

    public function test_menu_data_is_embedded_for_menu_page(): void
    {
        $this->get('/menu')
            ->assertOk()
            ->assertSee('id="pawon-menu"', false)
            ->assertSee('Botok Ontong');
    }

    public function test_catalog_from_database_matches_menu_json(): void
    {
        $json = json_decode(file_get_contents(resource_path('data/menu.json')), true);
        $catalog = app(MenuCatalog::class)->all();

        $this->assertSame($json['items'], $catalog['items']);
        $this->assertEqualsCanonicalizing($json['favorites'], $catalog['favorites']);
    }

    public function test_unavailable_menu_is_hidden_everywhere(): void
    {
        Menu::where('slug', 'botok-ontong')->update(['tersedia' => false]);

        $catalog = app(MenuCatalog::class)->all();

        $this->assertNotContains('botok-ontong', array_column($catalog['items'], 'id'));
        // Favorit yang tidak tersedia juga hilang, kalau tidak JS mencari menu yang tidak ada
        $this->assertNotContains('botok-ontong', $catalog['favorites']);
    }
}
