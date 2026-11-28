<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        $nama = fake()->randomElement([
            'Sayur Asem', 'Tempe Penyet', 'Ayam Bakar', 'Pepes Tuna',
            'Garang Asem', 'Bakwan', 'Teh Manis', 'Kopi Susu',
        ]);

        return [
            'slug' => Str::slug($nama) . '-' . fake()->unique()->numerify('####'),
            'nama_menu' => $nama,
            'keterangan' => fake('id_ID')->sentence(6),
            'kategori' => fake()->randomElement(['Menu Utama', 'Menu Daging', 'Menu Cemilan', "Menu Ombe'an"]),
            'subkategori' => null,
            'harga' => fake()->numberBetween(5, 30) * 1000,
            'harga_dingin' => null,
            'satuan' => 'porsi',
            'tags' => null,
            'foto' => null,
            'favorit' => false,
            'tersedia' => fake()->boolean(90),
        ];
    }

    public function tersedia(): static
    {
        return $this->state(fn () => ['tersedia' => true]);
    }

    public function tidakTersedia(): static
    {
        return $this->state(fn () => ['tersedia' => false]);
    }
}
