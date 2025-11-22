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
        Schema::create('reviews', function (Blueprint $table) {
            $table->string('id_review')->primary();
            $table->string('id_pengguna');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->string('id_produk');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            $table->text('komentar');
            $table->string('foto')->nullable();
            $table->string('video')->nullable();
            $table->string('kualitas');
            $table->string('kegunaan');
            $table->String('tampilkan_username');
            $table->int('rating_seller');
            $table->date('tanggal_review');
            $table->enum('status', ['tolak', 'setujui'])->default('setujui');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
