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
        Schema::create('album_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_pondok_id')->constrained('album_pondok')->onDelete('cascade');
            $table->string('foto');
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_cover')->default(false); // Foto profil album
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_fotos');
    }
};

