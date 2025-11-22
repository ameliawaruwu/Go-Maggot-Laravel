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
        Schema::create('produk', function (Blueprint $table) {
            $table->string('id_produk')->primary();
            $table->string('nama_produk');
            $table->text('deskripsi_produk');
            $table->string('kategori');
            $table->string('merk');
            $table->string('masa_penyimpanan');
            $table->string('pengiriman');
            $table->string('berat');
            $table->integer('harga')->default(0);
            $table->integer('stok')->default(0);
            $table->string('gambar')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
