<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('info_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->default('Aplikasi Manajemen Data Santri');
            $table->text('tentang')->nullable();
            $table->text('fitur')->nullable();
            $table->text('keamanan')->nullable();
            $table->text('bantuan')->nullable();
            $table->string('versi')->default('1.0.0');
            $table->string('framework')->default('Laravel 12');
            $table->string('database')->default('MySQL');
            $table->timestamps();
        });

        // Insert default data
        DB::table('info_aplikasi')->insert([
            [
                'judul' => 'Aplikasi Manajemen Data Santri',
                'tentang' => 'Aplikasi Manajemen Data Santri adalah sistem informasi yang dirancang khusus untuk mengelola data santri di Pondok Pesantren HS Al-Fakkar. Aplikasi ini memudahkan admin dalam mengelola data santri dan memungkinkan santri untuk melihat profil mereka.',
                'fitur' => "1. Manajemen data santri (CRUD)\n2. Profil santri dengan fitur cetak/unduh\n3. Profil pondok pesantren\n4. Album kegiatan pondok\n5. Dashboard admin dan santri\n6. Sistem login terpisah untuk admin dan santri",
                'keamanan' => 'Aplikasi ini dilengkapi dengan sistem autentikasi yang aman. Setiap pengguna memiliki akses sesuai dengan peran mereka (admin atau santri). Data santri hanya dapat diakses oleh admin dan santri yang bersangkutan.',
                'bantuan' => 'Jika Anda mengalami kesulitan dalam menggunakan aplikasi, silakan hubungi administrator pondok. Untuk pertanyaan teknis atau laporan bug, dapat menghubungi tim pengembang aplikasi.',
                'versi' => '1.0.0',
                'framework' => 'Laravel 12',
                'database' => 'MySQL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_aplikasi');
    }
};

