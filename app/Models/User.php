<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_whatsapp',
        'role',
        'session_token',
    ];

    protected $hidden = [
        'password',
        'session_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Arahkan fitur "remember me" bawaan Laravel ke kolom session_token.
     * Jadi Auth::attempt($credentials, remember: true) otomatis memakai kolom ini.
     */
    public function getRememberTokenName(): string
    {
        return 'session_token';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }
}
