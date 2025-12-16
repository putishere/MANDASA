# Perbaikan Auto Redirect ke Dashboard

## Masalah

Setiap kali menjalankan `php artisan serve` dan mengakses `http://127.0.0.1:8000/`, langsung redirect ke `/santri/dashboard` atau dashboard lainnya tanpa menampilkan halaman login.

## Penyebab

1. **Session masih tersimpan** dari login sebelumnya
2. **Route `/` tidak menggunakan middleware `guest`** sehingga tidak ada pengecekan
3. **Cookie browser masih ada** yang menyimpan session ID

## Solusi

### Langkah 1: Clear Session dan Cache

Jalankan script untuk menghapus semua session:

```bash
php clear_session.php
```

Atau manual:

```bash
# Hapus file session
rm -rf storage/framework/sessions/*

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Jika menggunakan database session:

```bash
php artisan tinker
```

```php
DB::table('sessions')->truncate();
```

### Langkah 2: Hapus Cookie Browser

1. Buka Developer Tools (F12)
2. Tab **Application** (Chrome) atau **Storage** (Firefox)
3. Di bagian **Cookies**, pilih `http://127.0.0.1:8000`
4. Hapus semua cookie, terutama:
   - `laravel_session`
   - `XSRF-TOKEN`
5. Atau gunakan mode **Incognito/Private Browsing**

### Langkah 3: Verifikasi Route

Route `/` sekarang sudah menggunakan middleware `guest`, jadi:
- Jika **belum login** → Tampilkan halaman login
- Jika **sudah login** → Redirect ke dashboard sesuai role

### Langkah 4: Test

1. Hapus semua session dan cookie
2. Buka browser baru atau incognito
3. Akses `http://127.0.0.1:8000/`
4. Seharusnya menampilkan **halaman login**, bukan redirect ke dashboard

## Perbaikan yang Sudah Dilakukan

1. ✅ **Route `/` menggunakan middleware `guest`**
   - Sekarang route `/` dan `/login` sama-sama menggunakan middleware `guest`
   - Jika user sudah login, akan otomatis redirect ke dashboard sesuai role
   - Jika user belum login, akan menampilkan halaman login

2. ✅ **Script Clear Session**
   - Dibuat `clear_session.php` untuk menghapus semua session dengan mudah
   - Script juga clear cache secara otomatis

## Alur Setelah Perbaikan

```
1. User mengakses http://127.0.0.1:8000/
   ↓
2. Middleware 'guest' mengecek apakah user sudah login
   ↓
3a. Jika BELUM login:
    → Tampilkan halaman login
   ↓
3b. Jika SUDAH login:
    → Redirect ke dashboard sesuai role
    → Admin → /admin/dashboard
    → Santri → /santri/dashboard
```

## Cara Mencegah Masalah Ini

1. **Selalu logout** sebelum menutup browser atau restart server
2. **Hapus cookie** jika ada masalah dengan session
3. **Gunakan incognito mode** untuk testing
4. **Jalankan `php clear_session.php`** jika ada masalah

## Catatan

- Session Laravel disimpan di `storage/framework/sessions/` (file session)
- Atau di tabel `sessions` (database session)
- Session akan tetap ada meskipun server di-restart
- Cookie browser menyimpan session ID, jadi session tetap aktif di browser yang sama

## Troubleshooting

Jika masih redirect otomatis setelah clear session:

1. **Cek apakah ada session yang tersimpan**:
   ```bash
   ls -la storage/framework/sessions/
   ```

2. **Cek cookie browser**:
   - Pastikan cookie `laravel_session` sudah dihapus
   - Gunakan incognito mode

3. **Cek route list**:
   ```bash
   php artisan route:list | grep -E "GET.*/"
   ```

4. **Cek middleware**:
   - Pastikan middleware `guest` terdaftar di `app/Http/Kernel.php`
   - Pastikan route menggunakan middleware `guest`

