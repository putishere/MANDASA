# 🔄 Panduan Migrasi Database dari SQLite ke MySQL

**Tanggal:** $(date)  
**Status:** Panduan Lengkap

---

## 📋 Persiapan

### 1. Backup Data SQLite

**PENTING:** Backup data SQLite terlebih dahulu!

```bash
# Copy file database SQLite
copy database\database.sqlite database\database_backup.sqlite
```

### 2. Pastikan MySQL/MariaDB Running

- Pastikan MySQL/MariaDB sudah running di Laragon
- Buka http://localhost/phpmyadmin untuk verifikasi

### 3. Buat Database MySQL

**Cara 1: Menggunakan phpMyAdmin**
1. Buka http://localhost/phpmyadmin
2. Klik tab "SQL"
3. Copy-paste script dari `docs/guides/create_database.sql`
4. Klik "Go"

**Cara 2: Menggunakan Command Line**
```bash
mysql -u root -p < docs/guides/create_database.sql
```

**Cara 3: Manual di phpMyAdmin**
1. Buka http://localhost/phpmyadmin
2. Klik "New" di sidebar kiri
3. Database name: `managemen_data_santri`
4. Collation: `utf8mb4_unicode_ci`
5. Klik "Create"

---

## 🔧 Konfigurasi .env

### Update File .env

Buka file `.env` dan update konfigurasi database:

```env
# Ganti dari SQLite ke MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=managemen_data_santri
DB_USERNAME=root
DB_PASSWORD=

# Comment atau hapus konfigurasi SQLite
# DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite
```

**Untuk Laragon (default):**
- Host: `127.0.0.1`
- Port: `3306`
- Username: `root`
- Password: (kosong)

---

## 🚀 Langkah Migrasi

### Opsi 1: Menggunakan Script Otomatis (Recommended)

```bash
# Jalankan script migrasi
php scripts/migrate_to_mysql.php
```

Script ini akan:
- ✅ Membuat backup SQLite otomatis
- ✅ Membuat database MySQL (jika belum ada)
- ✅ Menjalankan migration
- ✅ Memindahkan semua data dari SQLite ke MySQL
- ✅ Verifikasi data

### Opsi 2: Manual Step by Step

#### Step 1: Buat Database MySQL

```sql
CREATE DATABASE IF NOT EXISTS managemen_data_santri 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

#### Step 2: Update .env

Update konfigurasi database di `.env` seperti di atas.

#### Step 3: Clear Config Cache

```bash
php artisan config:clear
php artisan cache:clear
```

#### Step 4: Jalankan Migration

```bash
php artisan migrate:fresh
```

#### Step 5: Migrasi Data (Manual)

Jika ada data di SQLite yang ingin dipindahkan, gunakan script migrasi atau export/import manual.

---

## 📊 Verifikasi Migrasi

### 1. Test Koneksi

```bash
php artisan tinker
```

Di dalam tinker:
```php
DB::connection()->getPdo();
// Harus return PDO object tanpa error

DB::table('users')->count();
// Cek jumlah user
```

### 2. Cek Tabel

```bash
php artisan db:show
```

Atau di phpMyAdmin:
- Buka http://localhost/phpmyadmin
- Pilih database `managemen_data_santri`
- Cek semua tabel sudah ada

### 3. Test Aplikasi

1. Buka aplikasi di browser
2. Test login
3. Test semua fitur CRUD
4. Pastikan data tampil dengan benar

---

## 🔍 Troubleshooting

### Error: "Access denied for user"

**Solusi:**
- Cek username dan password di `.env`
- Pastikan user MySQL memiliki akses ke database
- Untuk Laragon, biasanya username `root` dan password kosong

### Error: "Unknown database"

**Solusi:**
- Pastikan database sudah dibuat
- Cek nama database di `.env` sesuai dengan yang dibuat
- Jalankan script `docs/guides/create_database.sql`

### Error: "Table already exists"

**Solusi:**
- Jika ingin fresh start: `php artisan migrate:fresh`
- Jika ingin keep data: `php artisan migrate`

### Data Tidak Muncul Setelah Migrasi

**Solusi:**
1. Cek apakah data benar-benar dipindahkan
2. Clear cache: `php artisan config:clear && php artisan cache:clear`
3. Cek log error di `storage/logs/laravel.log`

---

## 📝 Checklist Migrasi

- [ ] Backup data SQLite
- [ ] MySQL/MariaDB sudah running
- [ ] Database MySQL sudah dibuat
- [ ] File `.env` sudah diupdate ke MySQL
- [ ] Config cache sudah di-clear
- [ ] Migration sudah dijalankan
- [ ] Data sudah dipindahkan (jika ada)
- [ ] Koneksi database sudah ditest
- [ ] Aplikasi sudah ditest
- [ ] Semua fitur berfungsi dengan baik

---

## ⚠️ Catatan Penting

1. **Backup Selalu:** Selalu backup data sebelum migrasi
2. **Test di Development:** Test migrasi di development dulu sebelum production
3. **Keep SQLite Backup:** Simpan backup SQLite untuk jaga-jaga
4. **Clear Cache:** Selalu clear cache setelah perubahan konfigurasi

---

## 🎯 Setelah Migrasi

### 1. Update Dokumentasi

Update README.md untuk mencerminkan penggunaan MySQL.

### 2. Optimize Database

```sql
-- Optimize tabel setelah migrasi
OPTIMIZE TABLE users;
OPTIMIZE TABLE santri_detail;
-- dll
```

### 3. Setup Backup Otomatis

Setup backup database MySQL secara berkala untuk production.

---

**Status:** ✅ Siap untuk migrasi

