# REVISI PROPOSAL - TEKS YANG SUDAH DIREVISI

**Judul:** PERANCANGAN SISTEM INFORMASI MANAJEMEN DATA SANTRI BERBASIS WEB (STUDI KASUS PP HS AL-FAKKAR BANDAR KIDUL)

**Catatan:** Dokumen ini berisi teks yang sudah direvisi dan siap digunakan untuk mengganti bagian yang sesuai di proposal asli.

---

## BAB I - PENDAHULUAN

### A. Latar Belakang Masalah

Indonesia dikenal sebagai salah satu pusat pendidikan Islam terbesar di dunia, yang ditunjukkan oleh keberadaan lebih dari 30.000 pondok pesantren yang menaungi jutaan santri (Ramadhani 2025). Pesantren memiliki peran penting dalam membentuk karakter, budaya, serta kompetensi keislaman santri, sekaligus berfungsi sebagai lembaga pendidikan tradisional dengan akar historis yang kuat dalam perkembangan Islam di Nusantara (Setiawan et al. 2019). Dalam konteks pendidikan pesantren, santri tidak hanya memperoleh pembelajaran keagamaan, tetapi juga mengembangkan berbagai keterampilan dan kompetensi lain yang diajarkan di lingkungan pesantren (Muhidin et al. 2025). Tingginya kompleksitas peran pesantren dan santri tersebut menuntut adanya sistem manajemen data santri yang terencana, terstruktur, dan berbasis pendekatan modern.

Meskipun teknologi informasi terus berkembang, sebagian besar pesantren masih menghadapi berbagai permasalahan dalam pengelolaan data santri. **Pendataan identitas santri, riwayat pendidikan, data wali santri, hingga status keaktifan umumnya masih dilakukan secara manual** melalui arsip kertas tanpa sistem terintegrasi. Kondisi tersebut berdampak pada keterlambatan pencatatan, meningkatnya risiko kesalahan penginputan, sulitnya proses pencarian data, serta rendahnya tingkat efisiensi dan akurasi pengelolaan data santri.

---

## BAB II - KAJIAN PUSTAKA

### A. Landasan Teori

#### Manajemen Data

Manajemen data santri berbasis web berfokus pada pengelolaan informasi santri yang meliputi data pribadi (nama, alamat, dan kontak), riwayat pendidikan, **data wali santri**, serta status keaktifan santri. Pengelolaan tersebut dilakukan melalui operasi Create, Read, Update, Delete (CRUD), dengan pemanfaatan Data Definition Language (DDL) untuk perancangan struktur basis data MySQL dan Data Manipulation Language (DML) untuk pemeliharaan dan pengolahan data. Sistem dikembangkan menggunakan framework Laravel 12 LTS yang menerapkan arsitektur Model–View–Controller (MVC), Eloquent Object Relational Mapping (ORM) untuk pengelolaan relasi data, serta Role-Based Access Control (RBAC) guna menjamin keamanan dan pembatasan hak akses pengguna. Penerapan sistem ini secara langsung ditujukan untuk mengurangi kesalahan input manual, mempercepat proses pencarian data, serta meminimalkan risiko kehilangan data, sebagaimana permasalahan di Asrama Takhossus Fathul Qorib (ASTAFQO) Pondok Pesantren HS Al-Fakkar Bandar Kidul (Siti Fatimah Rahmila dan Novebri Novebri 2024).

#### Laravel

Laravel mengimplementasikan Eloquent Object Relational Mapping (ORM) dengan pola Active Record untuk mendukung operasi Create, Read, Update, Delete (CRUD) dan pengelolaan relasi data, seperti hubungan **one-to-one antara User dan SantriDetail**, dilengkapi fluent query builder serta eager loading untuk mencegah masalah N+1 query. Pada lapisan tampilan, Laravel menggunakan Blade Templating Engine dengan direktif seperti @if, @foreach, dan @extends guna membangun antarmuka yang dinamis, modular, dan mudah dipelihara. Pengaturan alur aplikasi didukung oleh sistem routing dan middleware yang memungkinkan penerapan arsitektur Representational State Transfer (RESTful), autentikasi, perlindungan Cross-Site Request Forgery (CSRF), serta otorisasi berbasis Role-Based Access Control (RBAC). Selain itu, Artisan Command Line Interface (CLI) menyediakan fasilitas scaffolding, migrasi basis data, seeding, dan manajemen antrian, sehingga Laravel efektif digunakan untuk pengembangan sistem informasi manajemen data santri berbasis web yang terstruktur, aman, dan skalabel (Sinlae et al. 2024).

#### PHP

PHP versi **8.2 atau lebih tinggi** menjadi landasan utama pengembangan Laravel dengan dukungan fitur modern seperti Just-In-Time (JIT) compiler, typed properties, union types, attributes, serta match expressions yang memperkuat keamanan tipe data. Konsep superglobal PHP seperti $\_GET, $\_POST, dan $\_SESSION dikelola secara terstruktur melalui Request Facade Laravel, sehingga meningkatkan keamanan dan keteraturan alur data. Prinsip Object-Oriented Programming PHP—meliputi class, trait, dan interface—diimplementasikan secara optimal pada Eloquent Model. Selain itu, penerapan namespace dan autoloading berbasis standar PSR-4 mendukung modularitas dan skalabilitas aplikasi. Mekanisme exception handling menggunakan try-catch serta custom exceptions juga diterapkan untuk memastikan manajemen kesalahan yang andal pada sistem informasi manajemen data santri (Bagaskara, Utama, dan Pratiwi 2024).

---

## BAB III - METODE PENELITIAN

### A. Batasan Masalah

Sistem informasi yang dikembangkan dibatasi pada pendataan santri di Asrama Takhossus Fathul Qorib (ASTAFQO) Pondok Pesantren HS Al-Fakkar Bandar Kidul, yang mencakup informasi pribadi santri, **data wali santri (nama wali, alamat wali, nomor HP wali)**, dan status keaktifan santri.

Pengembangan sistem dilakukan berbasis web menggunakan framework PHP Laravel, dengan fokus pada perancangan fitur inti yang mendukung proses pendataan dan pengelolaan data santri.

Penelitian tidak mencakup integrasi dengan sistem pendidikan lain, serta tidak membahas modul tambahan seperti pengelolaan absensi, pembayaran SPP, maupun manajemen sumber daya manusia secara menyeluruh.

Evaluasi sistem hanya difokuskan pada kelayakan fungsional dan penerimaan pengguna User Acceptance Test (UAT) sebelum implementasi, tanpa mencakup pemantauan jangka panjang setelah sistem diterapkan.

Sistem yang dirancang berfungsi sebagai wadah penyimpanan dan pengelolaan data internal pondok, dan belum mencakup fitur lanjutan seperti e-learning, modul keilmuan, atau layanan digital lainnya di luar kebutuhan manajemen data santri.

### C. Desain Pengembangan

#### Desain Basis Data

**Desain Basis Data:** Perancangan struktur relasional tabel `users` dan `santri_detail` dengan relasi one-to-one menggunakan Foreign Key untuk menyimpan data santri secara efisien. Tabel `users` menyimpan data dasar pengguna (id, name, username, email, password, tanggal_lahir, role), sedangkan tabel `santri_detail` menyimpan detail lengkap santri (user_id sebagai Foreign Key, nis, tahun_masuk, alamat_santri, nomor_hp_santri, foto, status_santri, nama_wali, alamat_wali, nomor_hp_wali). Struktur database dirancang hingga tingkat normalisasi ketiga (3NF) untuk menghilangkan redundansi data.

### D. Tempat dan Waktu Perancangan

#### Implementasi - Perangkat Lunak

**Perangkat Lunak (Software):**

-   Sistem Operasi: Windows 10/11 64-bit.
-   Editor Teks: Visual Studio Code.
-   Bahasa Pemrograman: PHP **8.2 atau lebih tinggi** (Kerangka Laravel 12).
-   Server Web: Apache (melalui Laragon).
-   Basis data: MySQL.
-   Browser: Google Chrome untuk men-debug tampilan.

#### Fitur Utama yang Dibangun

Fitur utama yang dibangun meliputi:

1. **Modul Autentikasi Pengguna:** Fitur login dan logout untuk keamanan akses sistem. Sistem menggunakan unified login yang dapat mendeteksi otomatis apakah pengguna adalah admin atau santri berdasarkan format input (email untuk admin, username untuk santri). Password dienkripsi menggunakan algoritma Bcrypt untuk keamanan maksimal.

2. **Manajemen Data Master Santri (CRUD):** Fitur untuk menambah, melihat, mengedit, dan menghapus data santri beserta data wali santri. Sistem dilengkapi dengan:

    - Upload foto santri dengan kemampuan crop gambar menggunakan Cropper.js
    - Validasi input untuk mencegah kesalahan data
    - Pencarian data santri berdasarkan nama, username, atau NIS
    - Filter data berdasarkan status keaktifan (aktif/boyong)
    - Pagination untuk kemudahan navigasi data

3. **Manajemen Data Wali Santri:** Fitur untuk mengelola data wali santri yang terintegrasi dalam form data santri, meliputi nama wali, alamat wali, dan nomor HP wali.

4. **Rekapitulasi Laporan:** Fitur untuk mencetak dan mengunduh profil santri dalam format PDF. Sistem menyediakan:
    - Tampilan print-friendly untuk profil santri
    - Download profil santri sebagai dokumen PDF
    - Format laporan yang rapi dan profesional

#### Fitur Tambahan (Opsional)

Selain fitur utama yang telah disebutkan, sistem juga dilengkapi dengan fitur-fitur pendukung berikut:

5. **Manajemen Profil Pondok:** Admin dapat mengelola informasi profil pondok pesantren yang dapat dilihat oleh santri melalui dashboard mereka.

6. **Album Pondok:** Admin dapat mengelola album foto kegiatan pondok pesantren dengan kategori yang berbeda (kegiatan belajar, ngaji, olahraga, keagamaan, sosial, acara pondok). Santri dapat melihat album foto tersebut untuk mengetahui aktivitas pondok.

7. **Info Aplikasi:** Admin dapat mengelola informasi tentang aplikasi yang ditampilkan kepada pengguna, termasuk deskripsi aplikasi dan panduan penggunaan.

8. **Pengaturan Aplikasi:** Admin dapat mengatur konfigurasi tampilan aplikasi, termasuk nama aplikasi, logo, warna tema, dan teks footer sesuai kebutuhan.

Fitur-fitur tambahan ini mendukung pengalaman pengguna yang lebih lengkap dan meningkatkan nilai guna sistem informasi manajemen data santri secara keseluruhan.

---

## BAB IV - PENUTUP

### A. Kesimpulan

Sistem informasi yang dirancang bertujuan untuk mentransformasi proses pengelolaan data santri di Pondok Pesantren HS Al-Fakkar yang sebelumnya bersifat manual menjadi sistem digital yang terintegrasi, guna meminimalisir risiko kehilangan data dan meningkatkan efisiensi waktu kerja pengurus. Sistem berhasil mengimplementasikan fitur utama berupa autentikasi pengguna dengan unified login, manajemen data santri lengkap dengan CRUD, manajemen data wali santri, serta rekapitulasi laporan dalam format PDF. Selain itu, sistem juga dilengkapi dengan fitur pendukung seperti manajemen profil pondok, album foto, dan pengaturan aplikasi yang meningkatkan nilai guna sistem secara keseluruhan.

Pengembangan sistem ini menggunakan framework Laravel 12 dengan arsitektur Model-View-Controller (MVC) yang menjamin keamanan data melalui enkripsi Bcrypt dan memudahkan pemeliharaan sistem di masa mendatang karena struktur kodenya yang modular.

Penggunaan metode Waterfall (SDLC) memberikan alur pengembangan yang sangat terstruktur dan linear, mulai dari tahap analisis kebutuhan hingga pengujian, sehingga setiap fitur yang dibangun dipastikan sesuai dengan desain awal yang telah disepakati bersama pihak mitra.

Rencana pengujian fungsional menggunakan metode Black Box Testing dan pengujian keberterimaan pengguna melalui User Acceptance Test (UAT) menjadi instrumen validasi utama untuk memastikan bahwa sistem layak diimplementasikan secara teknis dan mudah digunakan oleh pengurus asrama.

---

**Catatan Penggunaan:**

1. Teks di atas adalah versi yang sudah direvisi dan siap digunakan
2. Ganti bagian yang sesuai di proposal asli dengan teks di atas
3. Pastikan konsistensi format dan gaya penulisan dengan proposal asli
4. Periksa kembali semua referensi untuk memastikan tidak ada yang terlewat

---

**Dibuat oleh:** AI Assistant  
**Tanggal:** 28 Desember 2025  
**Versi:** 2.0 (Final - Hanya Teks Revisi)
