<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // Semua test class memakai DatabaseSeeder yang sama: SQLite di memori hanya di-seed sekali per run
    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_register_creates_customer_even_if_role_is_sent(): void
    {
        $this->post('/daftar', [
            'nama' => 'Sari',
            'email' => 'Sari@Example.com',
            'no_whatsapp' => '081234567890',
            'password' => 'rahasia123',
            'role' => 'admin',
        ])->assertRedirect(route('profile'));

        $user = User::firstWhere('email', 'sari@example.com');
        $this->assertNotNull($user);
        $this->assertSame('customer', $user->role);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_register_rejects_used_email(): void
    {
        User::factory()->create(['email' => 'sari@example.com']);

        $this->from('/daftar')->post('/daftar', [
            'nama' => 'Sari',
            'email' => 'sari@example.com',
            'no_whatsapp' => '081234567890',
            'password' => 'rahasia123',
        ])->assertRedirect('/daftar')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_returns_to_booking_after_guest_opened_it(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->get('/booking')->assertOk()->assertSee('Akun Diperlukan Untuk Booking');

        $this->post('/masuk', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('booking'));

        $this->get('/booking')->assertSee('id="wiz"', false)->assertDontSee('Akun Diperlukan Untuk Booking');
    }

    public function test_login_with_wrong_password_fails(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->from('/masuk')->post('/masuk', ['email' => $user->email, 'password' => 'salah'])
            ->assertRedirect('/masuk')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_profile_needs_login_and_shows_own_data(): void
    {
        $this->get('/profil')->assertRedirect(route('login'));

        $user = User::factory()->create(['nama' => 'Budi Santoso']);

        $this->actingAs($user)->get('/profil')
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee($user->email)
            ->assertSee(route('profile'), false);
    }

    public function test_logout(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/keluar')
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
