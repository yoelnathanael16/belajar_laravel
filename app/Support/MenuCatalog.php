<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * Sumber data menu Pawon Ratu Roso.
 *
 * Saat ini membaca resources/data/menu.json. Nanti bisa diganti ke tabel MySQL
 * cukup dengan mengubah isi all() agar mengembalikan struktur yang sama.
 */
class MenuCatalog
{
    /**
     * @return array{favorites: list<string>, items: list<array<string, mixed>>}
     */
    public function all(): array
    {
        return json_decode(
            File::get(resource_path('data/menu.json')),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );
    }
}
