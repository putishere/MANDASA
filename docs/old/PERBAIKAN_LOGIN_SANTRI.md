# Perbaikan Login Santri dan Input Data Santri

## Masalah yang Diperbaiki

1. **Username tidak bisa digunakan untuk login setelah input data santri**
   - **Penyebab**: Query login menggunakan exact match (case-sensitive) untuk username dan role
   - **Solusi**: Mengubah query menjadi case-insensitive dengan `LOWER(TRIM())`

2. **Validasi password tidak konsisten**
   - **Penyebab**: Login mencoba membandingkan plain text dengan hash secara langsung
   - **Solusi**: Hanya menggunakan `Hash::check()` untuk validasi password

3. **Username dengan whitespace tidak terdeteksi**
   - **Penyebab**: Username tidak di-trim saat create/update
   - **Solusi**: Normalisasi username dengan `trim()` saat create dan update

## Perubahan yang Dilakukan

### 1. AuthController.php - `trySantriLogin()`
- ✅ Query username menggunakan `LOWER(TRIM())` untuk case-insensitive search
- ✅ Query role menggunakan `LOWER(TRIM())` untuk case-insensitive search
- ✅ Hanya menggunakan `Hash::check()` untuk validasi password
- ✅ Menambahkan logging untuk debugging
- ✅ Auto-fix role jika tidak sesuai

### 2. SantriController.php - `store()` dan `update()`
- ✅ Normalisasi username dengan `trim()` saat create
- ✅ Normalisasi name dengan `trim()` saat create
- ✅ Normalisasi username dan name saat update
- ✅ Memastikan role selalu lowercase 'santri'

### 3. Script Perbaikan - `fix_santri_users.php`
- ✅ Script untuk memperbaiki data santri yang sudah ada
- ✅ Normalisasi username (trim whitespace)
- ✅ Normalisasi role menjadi lowercase 'santri'
- ✅ Memperbaiki password jika masih plain text

## Cara Menggunakan

### 1. Perbaiki Data Santri yang Sudah Ada

Jalankan script perbaikan untuk menormalisasi data santri yang sudah ada:

```bash
php fix_santri_users.php
```

**Catatan**: Di Windows dengan Laragon, jalankan melalui terminal Laragon atau pastikan PHP ada di PATH.

### 2. Test Login Santri

1. Buka halaman login: `http://127.0.0.1:8000/login`
2. Masukkan username santri (bukan email)
3. Masukkan password: tanggal lahir dalam format `YYYY-MM-DD` (contoh: `2005-08-15`)
4. Klik tombol "Masuk"
5. Harus redirect ke dashboard santri

### 3. Buat Data Santri Baru

1. Login sebagai admin
2. Buka menu "Data Santri" → "Tambah Santri"
3. Isi form dengan benar:
   - **Username**: Tidak perlu khawatir dengan case atau whitespace, akan otomatis di-trim
   - **Password**: Akan otomatis dibuat dari tanggal lahir (format YYYY-MM-DD)
4. Simpan data
5. Test login dengan username dan tanggal lahir sebagai password

## Tips

### Format Username
- Username akan otomatis di-trim (menghapus spasi di awal/akhir)
- Login sekarang case-insensitive (tidak peduli huruf besar/kecil)
- Contoh: `"Fauzi123"`, `"fauzi123"`, `" FAUZI123 "` semuanya akan match dengan `"fauzi123"` di database

### Format Password
- Default password adalah **tanggal lahir** dalam format `YYYY-MM-DD`
- Contoh: Jika tanggal lahir adalah `15 Agustus 2005`, maka password adalah `2005-08-15`
- Password harus diinput sesuai format (dengan tanda minus)

### Troubleshooting

#### Masalah: "Login gagal. Periksa kembali username/email dan password Anda."
**Solusi**:
1. Pastikan username yang diinput benar (akan otomatis di-trim)
2. Pastikan password adalah tanggal lahir dengan format `YYYY-MM-DD`
3. Jalankan script `fix_santri_users.php` untuk memperbaiki data yang sudah ada
4. Clear cache dan session:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

#### Masalah: "Halaman Kedaluwarsa (419 Error)"
**Solusi**:
1. Refresh halaman login
2. Clear browser cache dan cookies
3. Pastikan session driver sudah benar di `.env`:
   ```
   SESSION_DRIVER=file
   ```
4. Pastikan folder `storage/framework/sessions` writable

#### Masalah: Data santri tidak bisa login setelah dibuat
**Solusi**:
1. Pastikan data berhasil disimpan (cek di database atau list santri)
2. Pastikan `SantriDetail` sudah dibuat (harus ada relasi `santriDetail`)
3. Jalankan script `fix_santri_users.php`
4. Pastikan role adalah 'santri' (lowercase)

## Catatan Penting

1. **Password Default**: Password default adalah tanggal lahir. Jika ingin mengubah, perlu modifikasi di `SantriController@store` dan `SantriController@update`.

2. **Case Sensitivity**: Login sekarang case-insensitive untuk username dan role. Ini mempermudah user tanpa harus khawatir dengan huruf besar/kecil.

3. **Whitespace**: Username akan otomatis di-trim saat create/update. Login juga akan mencari dengan trim, jadi tidak perlu khawatir dengan spasi di awal/akhir.

4. **Role Normalization**: Role akan selalu dinormalisasi menjadi lowercase 'santri'. Jika ada data dengan role 'Santri' atau 'SANTRI', akan otomatis diperbaiki saat login.

## File yang Diubah

1. `app/Http/Controllers/AuthController.php`
   - Method `trySantriLogin()`: Perbaikan query dan validasi

2. `app/Http/Controllers/SantriController.php`
   - Method `store()`: Normalisasi username dan name
   - Method `update()`: Normalisasi username dan name

3. `fix_santri_users.php` (BARU)
   - Script untuk memperbaiki data santri yang sudah ada

## Status

✅ **Semua masalah sudah diperbaiki**
- Query login case-insensitive ✓
- Validasi password dengan Hash::check ✓
- Normalisasi username saat create/update ✓
- Auto-fix role jika tidak sesuai ✓
- Script perbaikan data existing ✓

Login santri sekarang seharusnya berfungsi dengan baik!

