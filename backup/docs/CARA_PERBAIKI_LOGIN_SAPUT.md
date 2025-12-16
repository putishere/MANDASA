# 🔧 CARA MEMPERBAIKI LOGIN USER SAPUT

## Masalah
Login dengan username "SAPUT" tidak berhasil karena user tidak ditemukan di database.

## Solusi

### **Cara 1: Menggunakan Route Perbaikan (Paling Mudah)**

1. **Pastikan server Laravel sudah berjalan**
   - Buka Laragon dan pastikan Apache/MySQL running
   - Atau jalankan: `php artisan serve`

2. **Buka browser dan akses URL berikut:**
   ```
   http://127.0.0.1:8000/fix-login-saput
   ```
   Atau jika menggunakan Laragon:
   ```
   http://managemen-data-santri.test/fix-login-saput
   ```

3. **Anda akan melihat hasil JSON** yang menunjukkan:
   - Status perbaikan (berhasil/gagal)
   - Informasi user SAPUT
   - **Kredensial login** (username dan password)

4. **Gunakan kredensial yang ditampilkan untuk login**

### **Cara 2: Menggunakan Script PHP**

Jika route tidak bisa diakses, jalankan script:

```powershell
# Gunakan PHP dari Laragon
C:\laragon\bin\php\php-8.2.x\php.exe fix_login_saput.php
```

Script akan:
- Mencari user SAPUT di database
- Jika tidak ada, membuat user baru
- Memperbaiki role, username, password
- Membuat SantriDetail jika belum ada
- Menampilkan kredensial login

---

## Kredensial Default Setelah Perbaikan

Setelah route/script dijalankan, kredensial login adalah:

- **Username**: `SAPUT`
- **Password**: `2005-09-14` (tanggal lahir default)

**Catatan**: Jika user sudah ada dan memiliki tanggal lahir yang berbeda, password akan disesuaikan dengan tanggal lahir tersebut.

---

## Setelah Perbaikan

1. **Coba login lagi** dengan kredensial yang ditampilkan
2. **Jika masih tidak bisa**, cek:
   - Pastikan user memiliki `santriDetail`
   - Pastikan role adalah `santri` (bukan `admin`)
   - Pastikan password sesuai dengan tanggal lahir (format: YYYY-MM-DD)

---

## Troubleshooting

### **Error "User tidak ditemukan"**
- Pastikan database sudah di-migrate: `php artisan migrate`
- Pastikan seeder sudah dijalankan: `php artisan db:seed`

### **Error "SantriDetail tidak bisa dibuat"**
- Pastikan tabel `santri_detail` sudah ada
- Cek migration: `php artisan migrate:status`

### **Masih tidak bisa login setelah perbaikan**
1. Clear browser cache dan cookies
2. Hapus session files: `del storage\framework\sessions\*.*`
3. Restart server
4. Coba login lagi dengan browser incognito

---

## Hapus Route Setelah Digunakan

**PENTING**: Setelah login berhasil, hapus route `/fix-login-saput` dari file `routes/web.php` untuk keamanan.

---

**Selamat mencoba!** 🎉

