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
        Schema::table('pesanan', function (Blueprint $table) {
            // PERBAIKAN: Tipe data disesuaikan menjadi string(255) agar cocok dengan tabel status_pesanan.
            // Nilai default diubah menjadi string ID yang sesuai dengan seeder (MENUNGGU_PEMBAYARAN).
            $table->string('id_status_pesanan', 255)->after('total_harga')->default('MENUNGGU_PEMBAYARAN'); 

            // Foreign key constraint tetap sama, karena nama kolomnya sama
            $table->foreign('id_status_pesanan')
                  ->references('id_status_pesanan')
                  ->on('status_pesanan')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['id_status_pesanan']); 
            $table->dropColumn('id_status_pesanan');
        });
    }
};