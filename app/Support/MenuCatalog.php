<?php

namespace App\Support;

use App\Models\Menu;

/**
 * Data menu untuk halaman pelanggan, diambil dari tabel menus.
 *
 * Bentuk datanya sama dengan resources/data/menu.json, supaya JavaScript
 * (resources/js/pawon/menu-data.js) tidak perlu diubah.
 */
class MenuCatalog
{
    /**
     * @return array{favorites: list<string>, items: list<array<string, mixed>>}
     */
    public function all(): array
    {
        // Menu yang dimatikan admin (tersedia = false) tidak tampil di website
        $menus = Menu::where('tersedia', true)->orderBy('id')->get();

        return [
            'favorites' => $menus->where('favorit', true)->pluck('slug')->values()->all(),
            'items' => $menus->map(fn (Menu $m) => [
                'id' => $m->slug,
                'name' => $m->nama_menu,
                'desc' => $m->keterangan ?? '',
                'price' => $m->harga,
                'unit' => $m->satuan,
                'cat' => $m->subkategori ? "{$m->kategori}|{$m->subkategori}" : $m->kategori,
                // Foto bawaan: nama file (dicari Pawon.img), foto unggahan admin: URL lengkap
                'img' => $m->foto && str_contains($m->foto, '/') ? $m->foto_url : ($m->foto ?? ''),
                'cold' => $m->harga_dingin ?? 0,
                'tags' => $m->tags ?? '',
            ])->all(),
        ];
    }
}
