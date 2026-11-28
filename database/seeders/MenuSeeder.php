<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['Nasi Kuning', 'Nasi kuning gurih dengan bumbu rempah khas.', 'Makanan Utama', 'Tumpeng', 25000],
            ['Nasi Putih', 'Nasi putih pulen.', 'Makanan Utama', 'Prasmanan', 8000],
            ['Ayam Bakar Madu', 'Ayam bakar dengan olesan madu dan bumbu kecap.', 'Lauk', 'Prasmanan', 22000],
            ['Ayam Goreng Kremes', 'Ayam goreng bumbu kuning dengan kremesan renyah.', 'Lauk', 'Nasi Box', 20000],
            ['Rendang Sapi', 'Daging sapi dimasak lama dengan santan dan rempah.', 'Lauk', 'Prasmanan', 35000],
            ['Sate Ayam', 'Sate ayam bumbu kacang, 10 tusuk per porsi.', 'Lauk', 'Prasmanan', 28000],
            ['Capcay Kuah', 'Tumis aneka sayur dengan kuah gurih.', 'Sayur', 'Prasmanan', 15000],
            ['Gado-Gado', 'Sayuran rebus dengan saus kacang.', 'Sayur', 'Prasmanan', 15000],
            ['Es Teh Manis', 'Teh manis dingin.', 'Minuman', 'Prasmanan', 5000],
            ['Es Buah', 'Aneka buah segar dengan sirup dan susu.', 'Dessert', 'Prasmanan', 12000],
            ['Pudding Coklat', 'Pudding coklat lembut dengan vla.', 'Dessert', 'Snack Box', 10000],
            ['Risoles Ragout', 'Risoles isi ragout ayam dan sayuran.', 'Snack', 'Snack Box', 8000],
            ['Kue Lapis', 'Kue lapis tradisional manis dan kenyal.', 'Snack', 'Snack Box', 6000],
        ];

        foreach ($menus as [$nama, $keterangan, $kategori, $jenis, $harga]) {
            Menu::create([
                'nama_menu' => $nama,
                'keterangan' => $keterangan,
                'kategori' => $kategori,
                'jenis' => $jenis,
                'harga' => $harga,
                'foto' => null,
                'tersedia' => true,
            ]);
        }
    }
}
