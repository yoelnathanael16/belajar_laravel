<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            // Kode tetap untuk link (#m-botok-ontong), menu favorit, dan chatbot
            $table->string('slug', 60)->unique();
            $table->string('nama_menu');
            $table->text('keterangan')->nullable();
            $table->string('kategori', 50);
            // Contoh: kategori "Menu 12k", subkategori "Nasi"
            $table->string('subkategori', 50)->nullable();
            $table->unsignedBigInteger('harga');
            // Harga versi dingin untuk minuman tertentu
            $table->unsignedBigInteger('harga_dingin')->nullable();
            $table->string('satuan', 20)->default('porsi');
            // Kata kunci untuk pencarian dan chatbot, dipisah spasi
            $table->string('tags')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('favorit')->default(false);
            $table->boolean('tersedia')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
