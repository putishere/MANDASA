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
        Schema::table('santri_detail', function (Blueprint $table) {
            // SQLite tidak mendukung tipe YEAR, gunakan INTEGER
            if (config('database.default') === 'sqlite') {
                $table->integer('tahun_masuk')->nullable()->after('nis');
            } else {
                $table->year('tahun_masuk')->nullable()->after('nis');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santri_detail', function (Blueprint $table) {
            $table->dropColumn('tahun_masuk');
        });
    }
};

