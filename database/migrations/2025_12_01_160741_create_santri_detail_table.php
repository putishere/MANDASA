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
        Schema::create('santri_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nis')->unique();                      // Nomor Induk Santri
            $table->string('alamat_santri');                      // Alamat santri
            $table->string('nomor_hp_santri')->nullable();        // Nomor HP santri
            $table->string('foto')->nullable();                   // Foto santri
            $table->enum('status_santri', ['aktif','boyong'])->default('aktif');
            $table->string('nama_wali');                          // Nama wali
            $table->string('alamat_wali');                        // Alamat wali
            $table->string('nomor_hp_wali')->nullable();          // Nomor HP wali
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri_detail');
    }
};