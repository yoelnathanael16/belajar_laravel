<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MenuSeeder extends Seeder
{
    /**
     * Isi tabel menus dari resources/data/menu.json (54 menu yang tampil di website).
     */
    public function run(): void
    {
        $data = json_decode(File::get(resource_path('data/menu.json')), true, flags: JSON_THROW_ON_ERROR);

        foreach ($data['items'] as $item) {
            // "Menu 12k|Nasi" -> kategori "Menu 12k", subkategori "Nasi"
            [$kategori, $subkategori] = array_pad(explode('|', $item['cat'], 2), 2, null);

            Menu::create([
                'slug' => $item['id'],
                'nama_menu' => $item['name'],
                'keterangan' => $item['desc'] ?: null,
                'kategori' => $kategori,
                'subkategori' => $subkategori,
                'harga' => $item['price'],
                'harga_dingin' => $item['cold'] ?: null,
                'satuan' => $item['unit'],
                'tags' => $item['tags'] ?: null,
                'foto' => $item['img'] ?: null,
                'favorit' => in_array($item['id'], $data['favorites'], true),
                'tersedia' => true,
            ]);
        }
    }
}
