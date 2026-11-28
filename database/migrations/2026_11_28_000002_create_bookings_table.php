<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_id');
            $table->string('kode_pesanan', 30)->unique();
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->cascadeOnDelete();
            $table->string('jenis_acara');
            $table->unsignedInteger('jumlah_tamu');
            $table->date('tanggal');
            $table->time('jam');
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'ditolak', 'selesai', 'dibatalkan'])
                ->default('menunggu');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            $table->index(['tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
