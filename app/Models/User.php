<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_whatsapp',
        // Aman: AuthController hanya menyimpan field yang sudah divalidasi, role tidak termasuk
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Halaman tujuan setelah masuk. */
    public function homeUrl(): string
    {
        return route($this->isAdmin() ? 'admin.dashboard' : 'profile');
    }

    /** Link chat WhatsApp ke nomor akun ini (08xx -> 628xx). */
    public function whatsappUrl(string $pesan = ''): string
    {
        $nomor = preg_replace('/^(0|62)/', '62', preg_replace('/\D/', '', $this->no_whatsapp));

        return "https://wa.me/{$nomor}" . ($pesan === '' ? '' : '?text=' . rawurlencode($pesan));
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
