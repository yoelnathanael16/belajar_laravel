<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PawonPagesTest extends TestCase
{
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
            'profile' => ['profile', '/profil'],
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
}
