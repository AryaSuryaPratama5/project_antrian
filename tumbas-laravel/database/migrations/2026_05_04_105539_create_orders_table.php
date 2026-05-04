<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_meja');
            $table->string('nama_pelanggan');
            $table->string('jenis_pesanan'); // Makan di Tempat / Bawa Pulang
            $table->text('detail_pesanan'); // Readable text
            $table->json('detail_json'); // Structured data
            $table->decimal('total_harga', 15, 2);
            $table->string('metode_bayar');
            $table->string('status_bayar')->default('Belum Bayar');
            $table->string('status_pelayanan')->default('Menunggu'); // Menunggu, Dimasak, Selesai
            $table->integer('estimasi_menit')->default(15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
