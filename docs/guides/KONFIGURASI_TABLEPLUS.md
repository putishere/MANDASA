# 🔌 Konfigurasi TablePlus untuk MySQL

## 📋 Pengaturan Koneksi MySQL di TablePlus

### Form MySQL Connection:

**Name:** `MANDASA` (atau nama lain yang Anda inginkan)

**Host/IP:** `127.0.0.1` (atau `localhost`)
- ❌ Jangan gunakan: `http://127.0.0.1:8000/` (itu URL web, bukan database)
- ✅ Gunakan: `127.0.0.1` saja

**Port:** `3306` (default MySQL)
- Jika menggunakan port lain, sesuaikan

**User:** `root` (default Laragon)
- Atau username MySQL Anda

**Password:** (kosongkan jika default Laragon)
- Atau password MySQL Anda

**Database:** `managemen_data_santri`
- Nama database yang akan dibuat/digunakan

**SSL mode:** `PREFERRED` (sudah benar)
- Atau bisa `DISABLED` untuk local development

**TLS:** `TLS 1.1, TLS 1.2` (sudah benar)

---

## ✅ Konfigurasi Lengkap

```
Name: MANDASA
Host/IP: 127.0.0.1
Port: 3306
User: root
Password: (kosong)
Database: managemen_data_santri
SSL mode: PREFERRED atau DISABLED
Tag: local
```

---

## 🔍 Cara Test Koneksi

1. Klik tombol **"Test"** di TablePlus
2. Jika berhasil, akan muncul pesan sukses
3. Jika gagal, cek:
   - MySQL/MariaDB sudah running di Laragon?
   - Database sudah dibuat?
   - Username dan password benar?

---

## 📝 Catatan

- **Host/IP** harus IP atau hostname, bukan URL web
- **Port 3306** adalah default MySQL
- **Database** harus sudah dibuat terlebih dahulu
- Untuk local development, SSL bisa di-disable

---

## 🚀 Langkah Selanjutnya

Setelah koneksi berhasil:
1. Buat database `managemen_data_santri` jika belum ada
2. Jalankan migration: `php artisan migrate`
3. Data akan muncul di TablePlus

