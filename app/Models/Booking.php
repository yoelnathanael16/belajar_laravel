<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'kode_pesanan',
        'user_id',
        'jenis_acara',
        'jumlah_tamu',
        'tanggal',
        'jam',
        'total_harga',
        'status',
        'alasan_penolakan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah_tamu' => 'integer',
            'total_harga' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class, 'booking_id');
    }
}
