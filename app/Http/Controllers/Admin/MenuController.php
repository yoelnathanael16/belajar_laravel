<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    private const MESSAGES = [
        'foto.*.image' => 'Foto harus berupa gambar (JPG, PNG, atau WebP).',
        'foto.*.max' => 'Ukuran foto maksimal 2 MB.',
        'foto.image' => 'Foto harus berupa gambar (JPG, PNG, atau WebP).',
        'foto.max' => 'Ukuran foto maksimal 2 MB.',
        'menus.*.harga.*' => 'Harga harus berupa angka 0 atau lebih.',
        'nama_menu.required' => 'Nama menu wajib diisi.',
        'kategori.*' => 'Pilih kategori menu.',
        'harga.*' => 'Harga harus berupa angka 0 atau lebih.',
    ];

    public function index(): View
    {
        return view('admin.menus', [
            'menus' => Menu::orderBy('id')->get(),
            'kategori' => Menu::kategoriOptions(),
        ]);
    }

    /** Simpan semua perubahan tabel sekaligus (tombol "Simpan Perubahan"). */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'menus' => ['required', 'array'],
            'menus.*.harga' => ['required', 'integer', 'min:0', 'max:10000000'],
            'menus.*.keterangan' => ['nullable', 'string', 'max:255'],
            'menus.*.tersedia' => ['required', 'boolean'],
            'foto' => ['array'],
            'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], self::MESSAGES);

        DB::transaction(function () use ($request, $data) {
            foreach (Menu::whereIn('id', array_keys($data['menus']))->get() as $menu) {
                $menu->fill($data['menus'][$menu->id]);
                if ($file = $request->file("foto.{$menu->id}")) {
                    $this->gantiFoto($menu, $file);
                }
                $menu->save();
            }
        });

        return back()->with('status', 'Perubahan menu tersimpan.');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'kategori' => ['required', Rule::in(array_keys(Menu::kategoriOptions()))],
            'harga' => ['required', 'integer', 'min:0', 'max:10000000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], self::MESSAGES);

        [$kategori, $subkategori] = array_pad(explode('|', $data['kategori'], 2), 2, null);

        $menu = new Menu([
            'slug' => $this->slugBaru($data['nama_menu']),
            'nama_menu' => $data['nama_menu'],
            'keterangan' => $data['keterangan'] ?? null,
            'kategori' => $kategori,
            'subkategori' => $subkategori,
            'harga' => $data['harga'],
            'satuan' => 'porsi',
            'tersedia' => $request->boolean('tersedia'),
        ]);
        if ($file = $request->file('foto')) {
            $this->gantiFoto($menu, $file);
        }
        $menu->save();

        return back()->with('status', "Menu {$menu->nama_menu} ditambahkan.");
    }

    private function gantiFoto(Menu $menu, UploadedFile $file): void
    {
        // Hapus foto unggahan lama. Foto bawaan (tanpa folder) ada di resources dan tidak dihapus.
        if ($menu->foto && str_contains($menu->foto, '/')) {
            Storage::disk('public')->delete($menu->foto);
        }
        $menu->foto = $file->store('menu', 'public');
    }

    private function slugBaru(string $nama): string
    {
        $dasar = Str::slug($nama) ?: 'menu';
        $slug = $dasar;
        for ($i = 2; Menu::where('slug', $slug)->exists(); $i++) {
            $slug = "{$dasar}-{$i}";
        }

        return $slug;
    }
}
