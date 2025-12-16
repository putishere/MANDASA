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
        Schema::create('profil_pondok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pondok')->default('PP HS AL-FAKKAR');
            $table->string('subtitle')->nullable();
            $table->text('tentang')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('program_unggulan')->nullable();
            $table->text('fasilitas')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        // Insert default data
        DB::table('profil_pondok')->insert([
            [
                'nama_pondok' => 'PP HS AL-FAKKAR',
                'subtitle' => 'Pondok Pesantren HS Al-Fakkar',
                'tentang' => 'Pondok Pesantren HS Al-Fakkar adalah lembaga pendidikan Islam yang berkomitmen untuk mencetak generasi yang berakhlak mulia, berilmu, dan bermanfaat bagi masyarakat. Pondok ini mengintegrasikan pendidikan agama dengan pengembangan karakter santri melalui sistem pembelajaran yang komprehensif.',
                'visi' => 'Menjadi pondok pesantren yang unggul dalam mencetak generasi muslim yang berakhlak mulia, berilmu, dan bermanfaat bagi umat.',
                'misi' => "1. Menyelenggarakan pendidikan agama Islam yang berkualitas\n2. Mengembangkan karakter dan akhlak mulia santri\n3. Membekali santri dengan ilmu pengetahuan yang bermanfaat\n4. Membina hubungan yang baik antara santri, ustadz, dan masyarakat\n5. Mengembangkan potensi santri dalam berbagai bidang",
                'program_unggulan' => "Sorogan: Sistem pembelajaran individual dengan bimbingan langsung dari ustadz.\nBandongan: Pembelajaran klasikal dengan metode ceramah dan diskusi.\nNgaji: Pembelajaran Al-Qur'an dan kitab-kitab klasik dengan metode tradisional.\nPengembangan Karakter: Program pembinaan akhlak dan karakter santri melalui berbagai kegiatan.",
                'fasilitas' => "1. Asrama yang nyaman dan bersih\n2. Perpustakaan dengan koleksi kitab yang lengkap\n3. Masjid untuk kegiatan ibadah dan pembelajaran\n4. Ruang kelas yang memadai\n5. Area olahraga dan rekreasi",
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
        Schema::dropIfExists('profil_pondok');
    }
};

