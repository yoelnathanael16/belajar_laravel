<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    // Pilihan yang sama dengan form booking (resources/js/pawon/booking.js)
    public const JENIS_ACARA = ['Ulang Tahun', 'Acara Kantor / Organisasi', 'Reuni / Kumpul Keluarga', 'Lainnya'];

    public const JUMLAH_TAMU = ['35-50 orang', '50-70 orang', '70-100 orang', 'Lebih dari 100 orang'];

    // Jam buka 08.00 - 17.00, kedatangan terakhir 16.00
    public const JAM = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];

    // Nilai di database -> teks yang tampil di desain
    public const STATUS_LABEL = [
        'menunggu' => 'Menunggu konfirmasi',
        'dikonfirmasi' => 'Diterima',
        'ditolak' => 'Ditolak',
        'selesai' => 'Selesai',
    ];

    protected $fillable = [
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
            'total_harga' => 'integer',
        ];
    }

    /** Kode pesanan untuk pelanggan dan admin, mis. PRS-0014. */
    protected function kode(): Attribute
    {
        return Attribute::get(fn () => 'PRS-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT));
    }

    /** Jam seperti di desain, mis. "13.00". */
    protected function jamLabel(): Attribute
    {
        return Attribute::get(fn () => str_replace(':', '.', substr($this->jam, 0, 5)));
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABEL[$this->status];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }
}
