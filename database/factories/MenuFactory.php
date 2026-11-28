<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        $nama = fake()->randomElement([
            'Nasi Kuning', 'Nasi Liwet', 'Ayam Bakar Madu', 'Ayam Goreng Kremes',
            'Rendang Sapi', 'Sate Ayam', 'Soto Betawi', 'Gado-Gado',
            'Pepes Ikan', 'Capcay Kuah', 'Es Teh Manis', 'Es Jeruk',
            'Pudding Coklat', 'Es Buah', 'Risoles', 'Kue Lapis',
        ]);

        return [
            'nama_menu' => $nama,
            'keterangan' => fake('id_ID')->sentence(10),
            'kategori' => fake()->randomElement(['Makanan Utama', 'Lauk', 'Sayur', 'Minuman', 'Dessert', 'Snack']),
            'jenis' => fake()->randomElement(['Prasmanan', 'Nasi Box', 'Tumpeng', 'Snack Box']),
            'harga' => fake()->numberBetween(5, 150) * 1000,
            'foto' => null,
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
