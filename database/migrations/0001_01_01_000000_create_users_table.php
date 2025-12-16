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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                               // nama lengkap
            $table->string('username')->unique()->nullable();     // untuk santri
            $table->date('tanggal_lahir')->nullable();            // untuk santri
            $table->string('email')->unique()->nullable();        // untuk admin
            $table->string('password');
            $table->enum('role', ['admin','santri']);             // role pemisah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};