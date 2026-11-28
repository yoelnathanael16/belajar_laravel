# Pawon Ratu Roso di Laravel

Frontend Pawon Ratu Roso sudah dipindah ke `resources/` dan dilayani lewat Blade + Vite.

## Menjalankan

```bash
npm install
npm run dev          # atau: npm run build
php artisan serve    # buka http://127.0.0.1:8000
```

Halaman: `/`, `/tentang-kami`, `/menu`, `/booking`, `/hubungi-kami`, `/masuk`, `/daftar`, `/profil`.
Halaman latihan Bootstrap yang lama ada di `/welcome`.

## Struktur

| Lokasi | Isi |
| --- | --- |
| `routes/web.php` | rute bernama (`home`, `about`, `menu`, `booking`, `contact`, `login`, `register`, `profile`) |
| `app/Http/Controllers/PageController.php` | satu method per halaman |
| `app/Support/MenuCatalog.php` | membaca menu yang `tersedia` dari tabel `menus` |
| `resources/data/menu.json` | sumber data awal untuk `MenuSeeder` (54 menu + menu favorit) |
| `resources/views/pawon/` | `layouts/app`, `partials/header`, `partials/footer`, satu view per halaman |
| `resources/css/pawon.css` | entry CSS (mengimpor `pawon/style.css` dan `pawon/mobile.css`) |
| `resources/js/pawon/` | skrip lama (modul) + `pages/*.js` sebagai entry Vite per halaman |
| `resources/images/pawon/` | foto dan logo, dipakai lewat `Vite::asset()` / `Pawon.img()` |

## Cara kerja singkat

- Layout mengirim peta URL ke JS lewat `data-routes` dan nama halaman aktif lewat `data-page` di `<body>`.
  JS memakai `Pawon.url('menu.html')` untuk mengubah nama berkas lama menjadi URL rute Laravel.
- Data menu dikirim Blade sebagai `<script type="application/json" id="pawon-menu">`, dibaca oleh `menu-data.js`.
- Halaman Pawon tidak memuat Bootstrap (`resources/css/app.css` tetap untuk halaman latihan), agar reset Bootstrap tidak mengubah tampilan.
- Setelah mengubah migration atau seeder, jalankan `php artisan migrate:fresh --seed`.
- Akun, booking, dan pesanan masih disimpan di `localStorage` browser (sama seperti versi HTML). Langkah berikutnya: pindahkan ke tabel MySQL.
