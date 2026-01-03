# REVISI PROPOSAL SESUAI DENGAN PROYEK AKTUAL

**Judul:** PERANCANGAN SISTEM INFORMASI MANAJEMEN DATA SANTRI BERBASIS WEB (STUDI KASUS PP HS AL-FAKKAR BANDAR KIDUL)

**Tanggal Revisi:** 28 Desember 2025

---

## 📝 DAFTAR PERUBAHAN YANG PERLU DILAKUKAN

### **PRIORITAS TINGGI - WAJIB DIPERBAIKI**

#### 1. **HAPUS SEMUA REFERENSI KE "MANAJEMEN DATA ASRAMA"**

**Lokasi yang Perlu Direvisi:**

**BAB I - Latar Belakang Masalah:**
- ❌ **HAPUS:** "Pendataan identitas santri, riwayat pendidikan, **pengaturan penempatan kamar**, hingga status keaktifan umumnya masih dilakukan secara manual"
- ✅ **GANTI DENGAN:** "Pendataan identitas santri, riwayat pendidikan, hingga status keaktifan umumnya masih dilakukan secara manual"

**BAB II - Landasan Teori (Manajemen Data):**
- ❌ **HAPUS:** "Manajemen data santri berbasis web berfokus pada pengelolaan informasi santri yang meliputi data pribadi (nama, alamat, dan kontak), riwayat pendidikan, **penempatan kamar**, serta status keaktifan santri."
- ✅ **GANTI DENGAN:** "Manajemen data santri berbasis web berfokus pada pengelolaan informasi santri yang meliputi data pribadi (nama, alamat, dan kontak), riwayat pendidikan, serta status keaktifan santri."

**BAB II - Landasan Teori (Laravel):**
- ❌ **HAPUS:** "Laravel mengimplementasikan Eloquent Object Relational Mapping (ORM) dengan pola Active Record untuk mendukung operasi Create, Read, Update, Delete (CRUD) dan pengelolaan relasi data, seperti hubungan satu-ke-banyak antara **santri dan kamar**"
- ✅ **GANTI DENGAN:** "Laravel mengimplementasikan Eloquent Object Relational Mapping (ORM) dengan pola Active Record untuk mendukung operasi Create, Read, Update, Delete (CRUD) dan pengelolaan relasi data, seperti hubungan one-to-one antara User dan SantriDetail"

**BAB III - Batasan Masalah:**
- ❌ **HAPUS:** "Sistem informasi yang dikembangkan dibatasi pada pendataan santri di Asrama Takhossus Fathul Qorib (ASTAFQO) Pondok Pesantren HS Al-Fakkar Bandar Kidul, yang mencakup informasi pribadi santri **dan administrasi dasar terkait data internal pesantren**."
- ✅ **GANTI DENGAN:** "Sistem informasi yang dikembangkan dibatasi pada pendataan santri di Asrama Takhossus Fathul Qorib (ASTAFQO) Pondok Pesantren HS Al-Fakkar Bandar Kidul, yang mencakup informasi pribadi santri, data wali santri, dan status keaktifan santri."

**BAB III - Tahap Desain:**
- ❌ **HAPUS:** "**Desain Basis Data:** Perancangan struktur relasional tabel dan relasi antar tabel (ERD) untuk menyimpan data secara efisien."
- ✅ **GANTI DENGAN:** "**Desain Basis Data:** Perancangan struktur relasional tabel `users` dan `santri_detail` dengan relasi one-to-one menggunakan Foreign Key untuk menyimpan data santri secara efisien."

**BAB III - Fitur Utama:**
- ❌ **HAPUS:** "**Manajemen Data Master Santri (CRUD):** Fitur untuk menambah, melihat, mengedit, dan menghapus data santri."
- ✅ **GANTI DENGAN:** "**Manajemen Data Master Santri (CRUD):** Fitur untuk menambah, melihat, mengedit, dan menghapus data santri beserta data wali santri. Sistem juga dilengkapi dengan fitur upload foto santri dengan kemampuan crop gambar, pencarian data santri berdasarkan nama/NIS, dan filter berdasarkan status keaktifan."

#### 2. **SESUAIKAN VERSI PHP**

**Lokasi yang Perlu Direvisi:**

**BAB III - Implementasi (Perangkat Lunak):**
- ❌ **HAPUS:** "Bahasa Pemrograman: PHP 8.3 (Kerangka Laravel 12)."
- ✅ **GANTI DENGAN:** "Bahasa Pemrograman: PHP 8.2 atau lebih tinggi (Kerangka Laravel 12)."

**BAB II - Landasan Teori (PHP):**
- ❌ **HAPUS:** "PHP versi 8.3+ menjadi landasan utama pengembangan Laravel"
- ✅ **GANTI DENGAN:** "PHP versi 8.2 atau lebih tinggi menjadi landasan utama pengembangan Laravel"

#### 3. **PERJELAS FITUR REKAPITULASI LAPORAN**

**Lokasi yang Perlu Direvisi:**

**BAB III - Fitur Utama:**
- ❌ **HAPUS:** "**Rekapitulasi Laporan:** Fitur untuk mencetak laporan data santri dan asrama."
- ✅ **GANTI DENGAN:** "**Rekapitulasi Laporan:** Fitur untuk mencetak dan mengunduh profil santri dalam format PDF. Sistem menyediakan tampilan print-friendly untuk profil santri yang dapat dicetak langsung melalui browser atau diunduh sebagai dokumen PDF."

---

### **PRIORITAS SEDANG - DISARANKAN DITAMBAHKAN**

#### 4. **TAMBAHKAN FITUR TAMBAHAN SEBAGAI NILAI TAMBAH (OPSIONAL)**

**Lokasi yang Bisa Ditambahkan:**

**BAB III - Fitur Utama (Tambahan):**

Tambahkan bagian baru setelah fitur utama:

```
**Fitur Tambahan:**

Selain fitur utama yang telah disebutkan, sistem juga dilengkapi dengan fitur-fitur pendukung berikut:

1. **Manajemen Profil Pondok:** Admin dapat mengelola informasi profil pondok pesantren yang dapat dilihat oleh santri.

2. **Album Pondok:** Admin dapat mengelola album foto kegiatan pondok pesantren. Santri dapat melihat album foto tersebut.

3. **Info Aplikasi:** Admin dapat mengelola informasi tentang aplikasi yang ditampilkan kepada pengguna.

4. **Pengaturan Aplikasi:** Admin dapat mengatur konfigurasi tampilan aplikasi sesuai kebutuhan.

Fitur-fitur tambahan ini mendukung pengalaman pengguna yang lebih lengkap dan meningkatkan nilai guna sistem informasi manajemen data santri.
```

**BAB IV - Kesimpulan:**

Tambahkan kalimat:
```
Selain fitur utama yang direncanakan, sistem juga dilengkapi dengan fitur tambahan seperti manajemen profil pondok, album foto, dan pengaturan aplikasi yang meningkatkan nilai guna sistem secara keseluruhan.
```

---

### **PRIORITAS RENDAH - PERBAIKAN TEKNIS**

#### 5. **PERBAIKAN DETAIL TEKNIS**

**BAB III - Desain Pengembangan:**

**Perbaikan Arsitektur Three-Tier:**
- Pastikan gambar arsitektur menunjukkan:
  - **Presentation Layer:** Blade Templates (View)
  - **Application Layer:** Laravel Controllers & Models
  - **Data Layer:** MySQL Database

**Perbaikan Desain Basis Data:**
- Tambahkan penjelasan tabel yang digunakan:
  ```
  **Tabel yang Digunakan:**
  
  1. **Tabel `users`:**
     - id (Primary Key)
     - name
     - username
     - email
     - password (terenkripsi Bcrypt)
     - tanggal_lahir
     - role (admin/santri)
     - timestamps
     
  2. **Tabel `santri_detail`:**
     - id (Primary Key)
     - user_id (Foreign Key ke users.id)
     - nis
     - tahun_masuk
     - alamat_santri
     - nomor_hp_santri
     - foto
     - status_santri (aktif/boyong)
     - nama_wali
     - alamat_wali
     - nomor_hp_wali
     - timestamps
  ```

---

## 📋 CHECKLIST REVISI PROPOSAL

### **Bagian yang Harus Direvisi:**

- [ ] **BAB I - Latar Belakang Masalah**
  - [ ] Hapus referensi "pengaturan penempatan kamar"
  
- [ ] **BAB II - Kajian Pustaka**
  - [ ] Hapus referensi "penempatan kamar" di Manajemen Data
  - [ ] Hapus referensi "santri dan kamar" di Laravel
  - [ ] Ubah PHP 8.3+ menjadi PHP 8.2+
  
- [ ] **BAB III - Metode Penelitian**
  - [ ] Ubah PHP 8.3 menjadi PHP 8.2 atau lebih tinggi
  - [ ] Perjelas fitur rekapitulasi laporan
  - [ ] Perbaiki deskripsi desain basis data
  - [ ] Hapus referensi "manajemen data asrama"
  - [ ] (Opsional) Tambahkan fitur tambahan
  
- [ ] **BAB IV - Penutup**
  - [ ] (Opsional) Tambahkan referensi fitur tambahan di kesimpulan

---

## 📄 CONTOH TEKS YANG SUDAH DIPERBAIKI

### **Contoh 1: Latar Belakang Masalah (SEBELUM)**

> "Pendataan identitas santri, riwayat pendidikan, pengaturan penempatan kamar, hingga status keaktifan umumnya masih dilakukan secara manual melalui arsip kertas tanpa sistem terintegrasi."

### **Contoh 1: Latar Belakang Masalah (SESUDAH)**

> "Pendataan identitas santri, riwayat pendidikan, hingga status keaktifan umumnya masih dilakukan secara manual melalui arsip kertas tanpa sistem terintegrasi."

---

### **Contoh 2: Manajemen Data (SEBELUM)**

> "Manajemen data santri berbasis web berfokus pada pengelolaan informasi santri yang meliputi data pribadi (nama, alamat, dan kontak), riwayat pendidikan, penempatan kamar, serta status keaktifan santri."

### **Contoh 2: Manajemen Data (SESUDAH)**

> "Manajemen data santri berbasis web berfokus pada pengelolaan informasi santri yang meliputi data pribadi (nama, alamat, dan kontak), riwayat pendidikan, data wali santri, serta status keaktifan santri. Pengelolaan tersebut dilakukan melalui operasi Create, Read, Update, Delete (CRUD), dengan pemanfaatan Data Definition Language (DDL) untuk perancangan struktur basis data MySQL dan Data Manipulation Language (DML) untuk pemeliharaan dan pengolahan data."

---

### **Contoh 3: Fitur Utama (SEBELUM)**

> "**Rekapitulasi Laporan:** Fitur untuk mencetak laporan data santri dan asrama."

### **Contoh 3: Fitur Utama (SESUDAH)**

> "**Rekapitulasi Laporan:** Fitur untuk mencetak dan mengunduh profil santri dalam format PDF. Sistem menyediakan tampilan print-friendly untuk profil santri yang dapat dicetak langsung melalui browser atau diunduh sebagai dokumen PDF. Fitur ini memungkinkan admin dan santri untuk mendapatkan salinan data profil santri dalam format yang rapi dan profesional."

---

### **Contoh 4: PHP Version (SEBELUM)**

> "Bahasa Pemrograman: PHP 8.3 (Kerangka Laravel 12)."

### **Contoh 4: PHP Version (SESUDAH)**

> "Bahasa Pemrograman: PHP 8.2 atau lebih tinggi (Kerangka Laravel 12)."

---

## ✅ HASIL SETELAH REVISI

Setelah melakukan revisi sesuai dengan panduan di atas, proposal akan:

1. ✅ **100% sesuai** dengan implementasi proyek aktual
2. ✅ **Tidak ada referensi** ke fitur yang tidak ada (manajemen asrama)
3. ✅ **Spesifikasi teknis** sesuai dengan proyek (PHP 8.2+)
4. ✅ **Fitur yang dijelaskan** sesuai dengan yang terimplementasi
5. ✅ **Dokumentasi** akurat dan dapat dipertanggungjawabkan

---

## 📌 CATATAN PENTING

1. **Jangan menghapus bagian penting** yang sudah sesuai dengan proyek
2. **Pastikan konsistensi** dalam penggunaan istilah teknis
3. **Periksa kembali** semua referensi ke "asrama" atau "kamar" sebelum finalisasi
4. **Sesuaikan gambar/diagram** jika ada yang menunjukkan fitur asrama
5. **Update daftar fitur** di bagian ringkasan jika ada perubahan

---

**Dibuat oleh:** AI Assistant  
**Tanggal:** 28 Desember 2025  
**Versi:** 1.0

