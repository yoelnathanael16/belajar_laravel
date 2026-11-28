<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            // Kode pesanan (PRS-0001) dibuat dari id, lihat Booking::kode
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('jenis_acara');
            // Rentang dari form booking, mis. "35-50 orang"
            $table->string('jumlah_tamu', 30);
            $table->date('tanggal');
            $table->time('jam');
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'ditolak', 'selesai'])
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
