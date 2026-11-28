<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'nama_menu',
        'keterangan',
        'kategori',
        'subkategori',
        'harga',
        'harga_dingin',
        'satuan',
        'tags',
        'foto',
        'favorit',
        'tersedia',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'harga_dingin' => 'integer',
            'favorit' => 'boolean',
            'tersedia' => 'boolean',
        ];
    }

    /**
     * URL foto. Nama file saja (mis. "botok.jpg") = foto bawaan di resources/images/pawon,
     * path dengan folder (mis. "menu/abc.jpg") = foto yang diunggah admin ke disk public.
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::get(fn () => match (true) {
            ! $this->foto => null,
            str_contains($this->foto, '/') => Storage::disk('public')->url($this->foto),
            default => Vite::asset("resources/images/pawon/{$this->foto}"),
        });
    }

    /** Mis. "Menu 12k : Nasi", sama seperti di desain admin. */
    protected function kategoriLabel(): Attribute
    {
        return Attribute::get(fn () => $this->subkategori ? "{$this->kategori} : {$this->subkategori}" : $this->kategori);
    }

    /**
     * Pilihan kategori untuk menu baru: hanya kategori yang sudah ada,
     * karena halaman Menu pelanggan menampilkan kategori dengan urutan tetap.
     *
     * @return array<string, string> nilai form ("Menu 12k|Nasi") => label ("Menu 12k : Nasi")
     */
    public static function kategoriOptions(): array
    {
        return self::select('kategori', 'subkategori')->distinct()->orderBy('kategori')->orderBy('subkategori')->get()
            ->mapWithKeys(fn (self $m) => [
                $m->subkategori ? "{$m->kategori}|{$m->subkategori}" : $m->kategori => $m->kategori_label,
            ])->all();
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }
}
