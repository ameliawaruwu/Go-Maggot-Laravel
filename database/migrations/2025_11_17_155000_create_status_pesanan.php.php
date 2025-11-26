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
        Schema::create('status_pesanan', function (Blueprint $table) {
        $table->uuid('id_status_pesanan')->primary();
        $table->string('status')->default('Menunggu Pembayaran');
        $table->string('deskripsi');
        $table->integer('urutan_tampilan');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_pesanan');
    }
};
