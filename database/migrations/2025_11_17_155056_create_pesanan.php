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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->string('id_pesanan')->primary();
            $table->string('id_pengguna');  
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->string('nama_penerima');
            $table->string('alamat_pengiriman');
            $table->string('nomor_telepon', 20);
            $table->dateTime('tanggal_pesanan')->useCurrent();
            $table->enum('metode_pembayaran', ['COD', 'QRIS'])->default('QRIS');
            $table->integer('total_harga')->default(0);

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
