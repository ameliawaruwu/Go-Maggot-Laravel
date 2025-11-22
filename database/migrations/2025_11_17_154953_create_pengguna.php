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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->string('id_pengguna')->primary();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nomor_telepon', 20)->nullable();
            $table->string('alamat')->nullable();
            $table->string('foto_profil')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('reset_otp')->nullable();
            $table->string('reset_token')->nullable();
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->enum('role', ['admin', 'pelanggan'])->default('pelanggan');
            
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
